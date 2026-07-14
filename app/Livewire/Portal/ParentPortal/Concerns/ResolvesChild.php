<?php

namespace App\Livewire\Portal\ParentPortal\Concerns;

use App\Models\StudentRegistration;

trait ResolvesChild
{
    public $student_id;

    /**
     * Every child linked to the logged-in guardian. Selection is always
     * resolved from this collection so a parent can never load another
     * family's student by guessing an id.
     */
    protected function children()
    {
        return StudentRegistration::with(['course', 'batch'])
            ->where('guardian_user_id', auth()->id())
            ->get();
    }

    protected function resolveChild($children)
    {
        $requested = (int) ($this->student_id ?: request('student_id'));

        $child = $requested ? $children->firstWhere('id', $requested) : null;

        return $child ?? $children->first();
    }
}
