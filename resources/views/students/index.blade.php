<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Students') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage student records in one place.') }}
                </p>
            </div>

            <a
                href="{{ route('students.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >
                {{ __('Add Student') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div id="student-alert"></div>
            @if (session('status') === 'student-created')
                <div class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-sm text-green-700 dark:text-green-300">
                    {{ __('Student created successfully.') }}
                </div>
            @elseif (session('status') === 'student-updated')
                <div class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-sm text-green-700 dark:text-green-300">
                    {{ __('Student updated successfully.') }}
                </div>
            @elseif (session('status') === 'student-deleted')
                <div class="px-4 py-3 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-sm text-green-700 dark:text-green-300">
                    {{ __('Student deleted successfully.') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Student List') }}
                        </h3>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                            {{ trans_choice('{0} No students yet|{1} :count student|[2,*] :count students', $students->total(), ['count' => $students->total()]) }}
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('First Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('Last Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('Email') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('Student Number') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('Year Level') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap text-right">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody id="student-table" class="divide-y divide-gray-100 dark:divide-gray-700/80">
                            @forelse ($students as $student)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                                        {{ $student->first_name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium whitespace-nowrap">
                                        {{ $student->last_name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ $student->email }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-mono text-xs">
                                            {{ $student->student_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                                            {{ __($student->year_level_label) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ $student->course }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a
                                                href="{{ route('students.edit', $student) }}"
                                                class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                            >
                                                {{ __('Edit') }}
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('students.destroy', $student) }}"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this student?') }}');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md border border-red-200 dark:border-red-800 text-xs font-semibold uppercase tracking-wide text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition"
                                                >
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="mx-auto max-w-sm">
                                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('No students yet') }}
                                            </p>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('Add your first student to start building the list.') }}
                                            </p>
                                            <a
                                                href="{{ route('students.create') }}"
                                                class="mt-5 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition"
                                            >
                                                {{ __('Add Student') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>