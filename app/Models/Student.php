<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'first_name',
    'last_name',
    'email',
    'student_number',
    'year_level',
    'course',
])]
class Student extends Model
{
    use HasFactory;

    /**
     * Available year levels keyed by stored value.
     *
     * @return array<int, string>
     */
    public static function yearLevels(): array
    {
        return [
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
        ];
    }

    /**
     * Get the student's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the human-readable year level label.
     */
    public function getYearLevelLabelAttribute(): string
    {
        return static::yearLevels()[$this->year_level] ?? (string) $this->year_level;
    }
}
