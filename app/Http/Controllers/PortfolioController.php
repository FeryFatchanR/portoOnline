<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Organization;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stat;
use Illuminate\Contracts\View\View;

/**
 * Halaman portfolio publik (read-only, tanpa login).
 */
class PortfolioController extends Controller
{
    public function __invoke(): View
    {
        return view('portfolio', [
            'profile' => Profile::current(),
            'stats' => Stat::published()->get(),
            'projects' => Project::published()->get(),
            'skills' => Skill::published()->get(),
            'experiences' => Experience::published()->get(),
            'organizations' => Organization::published()->get(),
            'educations' => Education::published()->get(),
            'certifications' => Certification::published()->get(),
        ]);
    }
}
