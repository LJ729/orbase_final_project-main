@props(['student' => null])

@php
    $yearLevels = \App\Models\Student::yearLevels();
@endphp

<div>
    <x-input-label for="first_name" :value="__('First Name')" />
    <x-text-input
        id="first_name"
        name="first_name"
        type="text"
        class="mt-1 block w-full"
        :value="old('first_name', $student?->first_name)"
        required
        autofocus
    />
    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
</div>

<div>
    <x-input-label for="last_name" :value="__('Last Name')" />
    <x-text-input
        id="last_name"
        name="last_name"
        type="text"
        class="mt-1 block w-full"
        :value="old('last_name', $student?->last_name)"
        required
    />
    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
</div>

<div>
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input
        id="email"
        name="email"
        type="email"
        class="mt-1 block w-full"
        :value="old('email', $student?->email)"
        required
    />
    <x-input-error class="mt-2" :messages="$errors->get('email')" />
</div>

<div>
    <x-input-label for="student_number" :value="__('Student Number')" />
    <x-text-input
        id="student_number"
        name="student_number"
        type="text"
        class="mt-1 block w-full"
        :value="old('student_number', $student?->student_number)"
        required
    />
    <x-input-error class="mt-2" :messages="$errors->get('student_number')" />
</div>

<div>
    <x-input-label for="year_level" :value="__('Year Level')" />
    <select
        id="year_level"
        name="year_level"
        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        required
    >
        <option value="">{{ __('Select year level') }}</option>
        @foreach ($yearLevels as $value => $label)
            <option
                value="{{ $value }}"
                @selected((string) old('year_level', $student?->year_level) === (string) $value)
            >
                {{ __($label) }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('year_level')" />
</div>

<div>
    <x-input-label for="course" :value="__('Course')" />
    <x-text-input
        id="course"
        name="course"
        type="text"
        class="mt-1 block w-full"
        :value="old('course', $student?->course)"
        required
    />
    <x-input-error class="mt-2" :messages="$errors->get('course')" />
</div>
