import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

window.Echo.channel('students')
    .listen('.student.created', (e) => {
        const alertBox = document.getElementById('student-alert');
        const tableBody = document.getElementById('student-table');
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        const yearLabels = {
            1: "1st Year",
            2: "2nd Year",
            3: "3rd Year",
            4: "4th Year",
        };

        console.log(alertBox);
        
        if (alertBox) {
            alertBox.innerHTML = `
                <div class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-sm text-green-700 dark:text-green-300">
                    New Student added: ${e.first_name} ${e.last_name}
                </div>
            `;
        }
        
        if (tableBody) {
            tableBody.insertAdjacentHTML('afterbegin', 
                `
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                        ${e.first_name}
                    </td>

                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                        ${e.last_name}
                    </td>

                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                        ${e.email}
                    </td>

                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-mono text-xs">
                            ${e.student_number}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                            ${yearLabels[e.year_level] ?? `${e.year}th Year`}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                        ${e.course}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="inline-flex items-center gap-2">
                            <a
                                href="/students/${e.id}/edit"
                                class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="/students/${e.id}"
                                onsubmit="return confirm('Are you sure you want to delete this student?');"
                            >
                                <input type="hidden" name="_token" value="${csrfToken}">
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
                `
            );
        }
    });
