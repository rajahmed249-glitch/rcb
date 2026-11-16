<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'question_bn',
        'answer',
        'answer_bn',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDisplayQuestionAttribute(): string
    {
        return trim(($this->question_bn && app()->getLocale() === 'bn' ? $this->question_bn : $this->question));
    }

    public function getDisplayAnswerAttribute(): string
    {
        return trim(($this->answer_bn && app()->getLocale() === 'bn' ? $this->answer_bn : $this->answer));
    }
}
