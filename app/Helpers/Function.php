<?php

use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\Announcement;


function getBranchAll()
{
	$getBranch = DB::table('branchs')
		->where('branch_active', 'Y')
		->orderBy('sap_code', 'ASC')
		->get();
	return $getBranch;
}

function getBranchById()
{
	$branches = DB::table('branchs')
		->select('branch_code', 'branch_desc')
		->orderBy('branch_desc', 'ASC')
		->get();
	return $branches;
}

// function getUsersSection()
// {
// 	$getUsersSection = DB::table('u_section')
// 		->where('u_id', Auth::user()->id)
// 		->pluck('section')
// 		->toArray();
// 	return $getUsersSection;
// }

// dd($getUsersSection);

function getSectionAll()
{
	$getSection = DB::table('ho_sections')
		->select('id', 'section_th', 'section_en')
		->where('active', '1')
		->orderBy('id', 'asc')
		->get();
	return $getSection;
}

function getSectionAllADD()
{
	$getSectionAllADD = DB::table('ho_sections')
		->select('section_th', 'section_en')
		->orderBy('id', 'asc')
		->get();
	return $getSectionAllADD;
}

function getSectionWithBranch($branch)
{
	$getSectionWithBranch = DB::table('ho_sections')
		->select('section_th', 'section_en', 'id')
		->where('branch_code', $branch)
		->orderBy('id', 'asc')
		->get();

	return $getSectionWithBranch;
}


function getSection()
{
	$section = DB::table('u_section')
		->where('u_id', Auth::user()->id)
		->pluck('section')
		->toArray();

	return strtoupper(implode(' , ', $section));
}


if (!function_exists('Count_Pending')) {
	function Count_Pending()
	{
		$visibleSections = DB::table('u_section')
			->where('u_id', Auth::user()->id)
			->pluck('section')
			->toArray();

		$countDo = DB::table('tb_request')
			->where('status', '=', 1)
			->whereIn('to_section', $visibleSections)
			->count();

		return $countDo;
	}
}

if (!function_exists('Count_Assignment')) {
	function Count_Assignment()
	{
		$countDo = DB::table('tb_request')
			->where('status', '=', 2)
			// ->where('to_section', '=', Auth::user()->section)
			->where('to_user', '=', Auth::user()->id)
			->count();
		return $countDo;
	}
}

if (!function_exists('Count_Complete')) {
	function Count_Complete()
	{
		$countDo = DB::table('tb_request')
			->where('status', '=', 4)
			// ->where('create_by', '=', Auth::user()->section)
			->where('create_by', '=', Auth::user()->id)
			->count();
		return $countDo;
	}
}

if (!function_exists('CountYourMemo')) {
	function CountYourMemo()
	{
		$user = Auth::user();
		$section = DB::table('u_section')
			->where('u_id', Auth::user()->id)
			->pluck('section')
			->toArray();

		$query = Memo::where('status', '!=', 'completed');

		if ($user->role == 'executive') {
			$query->where('current_step', '1');
			$query->where('to_executive', $user->id);
		} elseif ($user->role == 'manager') {
			$query->where('current_step', '2');
			$query->whereIn('to_section', $section);
		} elseif ($user->role == 'division') {
		} elseif ($user->role == 'user') {
			$query->where('current_step', '3');
			$query->where('to_assign', $user->id);
		}

		return $query->count();
	}

	if (!function_exists('CountMemo')) {
		function CountMemo()
		{
			$user = Auth::user();
			$section = DB::table('u_section')
				->where('u_id', Auth::user()->id)
				->pluck('section')
				->toArray();

			$query = Memo::where('status', '!=', 'completed');
			$query->where('current_step', '4');
			$query->where('created_by', $user->id);

			return $query->count();
		}
	}

	if (!function_exists('CountQA')) {

		function CountQA()
		{
			$user = Auth::user();

			return Question::where(function ($q) use ($user) {
				$q->where('from_user', $user->id)
					->orWhereIn('id', function ($sub) use ($user) {
						$sub->select('question_id')
							->from('question_cc')
							->where('user_id', $user->id);
					});
			})
				->where('status', '!=', 'closed')
				->count();
		}
	}

	if (!function_exists('CountAnnouncement')) {

		function CountAnnouncement()
		{
			$user = Auth::user();

			// ✅ ถ้าเป็น admin เห็นทั้งหมด
			if ($user->role === 'admin') {
				return \App\Models\Announcement::count();
			}

			// ✅ ถ้าเป็น user กรองตาม section
			return \App\Models\Announcement::where(function ($q) use ($user) {

				$q->whereExists(function ($sub) use ($user) {
					$sub->select(DB::raw(1))
						->from('u_section as us')
						->join('announcement_section as ac', 'ac.section_id', '=', 'us.section_id')
						->where('us.u_id', $user->id)
						->whereColumn('ac.announcement_id', 'announcements.id');
				})

					->orWhereExists(function ($sub) use ($user) {
						$sub->select(DB::raw(1))
							->from('u_section as us')
							->join('announcement_cc as acc', 'acc.section_id', '=', 'us.section_id')
							->where('us.u_id', $user->id)
							->whereColumn('acc.announcement_id', 'announcements.id');
					});
			})->count();
		}
	}
}
