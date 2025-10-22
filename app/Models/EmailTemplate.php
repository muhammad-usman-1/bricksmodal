<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'subject',
        'body',
    ];

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public function render(array $variables = []): string
    {
        $replacements = collect($variables)->mapWithKeys(function ($value, $key) {
            return ['{{' . $key . '}}' => $value];
        })->toArray();

        $replacements['{{app_name}}'] = config('app.name');

        return strtr($this->body, $replacements);
    }

    public function renderSubject(array $variables = []): string
    {
        $replacements = collect($variables)->mapWithKeys(function ($value, $key) {
            return ['{{' . $key . '}}' => $value];
        })->toArray();

        $replacements['{{app_name}}'] = config('app.name');

        return strtr($this->subject, $replacements);
    }
}
