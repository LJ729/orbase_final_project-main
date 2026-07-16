import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

const escapeHtml = (value) =>
    String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');

const showStudentAlert = (message) => {
    const alertBox = document.getElementById('student-alert');

    if (!alertBox) {
        return;
    }

    alertBox.innerHTML = `
        <div class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-sm text-green-700 dark:text-green-300">
            ${message}
        </div>
    `;
};

const buildStudentRow = (student) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    return `
        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors" data-student-id="${escapeHtml(student.id)}">
            <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                ${escapeHtml(student.first_name)}
            </td>
            <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                ${escapeHtml(student.last_name)}
            </td>
            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                ${escapeHtml(student.email)}
            </td>
            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-mono text-xs">
                    ${escapeHtml(student.student_number)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                    ${escapeHtml(student.year_level_label)}
                </span>
            </td>
            <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                ${escapeHtml(student.course)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
                <div class="inline-flex items-center gap-2">
                    <a
                        href="${escapeHtml(student.edit_url)}"
                        class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    >
                        Edit
                    </a>
                    <form
                        method="POST"
                        action="${escapeHtml(student.destroy_url)}"
                        onsubmit="return confirm('Are you sure you want to delete this student?');"
                    >
                        <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button
                            type="submit"
                            class="inline-flex items-center px-3 py-1.5 rounded-md border border-red-200 dark:border-red-800 text-xs font-semibold uppercase tracking-wide text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    `;
};

const studentsChannel = window.Echo.channel('students');

studentsChannel.listen('.student.created', (student) => {
    const tableBody = document.getElementById('student-table');

    showStudentAlert(
        `New student added: ${escapeHtml(student.first_name)} ${escapeHtml(student.last_name)}`,
    );

    if (!tableBody) {
        return;
    }

    if (tableBody.querySelector(`[data-student-id="${student.id}"]`)) {
        return;
    }

    tableBody.querySelector('[data-empty-state]')?.remove();
    tableBody.insertAdjacentHTML('afterbegin', buildStudentRow(student));
});

studentsChannel.listen('.student.updated', (student) => {
    const tableBody = document.getElementById('student-table');
    const existingRow = tableBody?.querySelector(`[data-student-id="${student.id}"]`);

    showStudentAlert(
        `Student updated: ${escapeHtml(student.first_name)} ${escapeHtml(student.last_name)}`,
    );

    if (!tableBody) {
        return;
    }

    if (existingRow) {
        existingRow.outerHTML = buildStudentRow(student);
        return;
    }

    tableBody.querySelector('[data-empty-state]')?.remove();
    tableBody.insertAdjacentHTML('afterbegin', buildStudentRow(student));
});

studentsChannel.listen('.student.deleted', (student) => {
    const tableBody = document.getElementById('student-table');
    const existingRow = tableBody?.querySelector(`[data-student-id="${student.id}"]`);

    showStudentAlert(
        `Student deleted: ${escapeHtml(student.first_name)} ${escapeHtml(student.last_name)}`,
    );

    if (!existingRow) {
        return;
    }

    existingRow.remove();

    if (tableBody && tableBody.children.length === 0) {
        tableBody.insertAdjacentHTML(
            'beforeend',
            `
                <tr data-empty-state>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="mx-auto max-w-sm">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                No students yet
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Add your first student to start building the list.
                            </p>
                        </div>
                    </td>
                </tr>
            `,
        );
    }
});