<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{State, City, Category, Size, NeighborhoodCity};

class AjaxController extends Controller
{
    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
    }

    public function menuSetup(Request $request)
    {
        if (session()->has('menu_option')) {
            session()->forget('menu_option');
        }

        if ($request->menu == 'open') {
            session(['menu_option' => 'open']);
        } else {
            session(['menu_option' => 'closed']);
        }

        return response()->json(['title' => 'Success', 'data' => session('menu_option'), 'message' => 'Menu changed successfully']);
    }

    public function states(Request $request, $countryId)
    {
        // dd("here state data ");
        $states = State::where('country_id', $countryId)->get();
        return response()->json($states);
        // return response()->json(['title' => 'Success', 'data' => $states, 'message' => 'States retrieved successfully']);
    }

    public function cities(Request $request,$stateId)
    {
        // $stateId = $request->state_id;
        $cities = City::where('state_id', $stateId)->get();

        return response()->json($cities);
        // return response()->json(['title' => 'Success', 'data' => $cities, 'message' => 'Cities retrieved successfully']);
    }

    public function get_subcat($id)
    {
        try {
            $id = jsdecode_userdata($id);
            if ((int) $id) {
                $category = Category::with(['size_type'])->where('status', '1')->where('id', $id)->first();
                $types = isset($category) && count($category->size_type) > 0 ? $category->size_type : [];
                $sub_categories = Category::where('status', '1')->where('parent_id', $id)->get();
                $sizes = Size::where('status', '1')->get();

                return response()->json([
                    'status' => true,
                    'message' => "Sub category fetch successfully",
                    'data'    => view('retailer.include.subcategory', compact('sub_categories'))->render(),
                    // 'type'    => view('retailer.include.type', compact('types'))->render(),
                    'size'    => view('retailer.include.size', compact('sizes'))->render(),
                    'parent'    => ($category->parent_id != null) ? 'sub' : 'main',
                ], 200);
            } else {
                $type = $id;
                $sizes = Size::where('status', '1')->where('type', $type)->get();
                return response()->json([
                    'status' => true,
                    'message' => "size fetch successfully",
                    'size'    => view('retailer.include.size', compact('sizes'))->render(),
                    'get_other' => ($type == 'other') ? '1' : '0',

                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function get_city($id)
    {
        // dd($id);
        if ($id) {
            $neighborhood = NeighborhoodCity::where('parent_id', $id)->get();
        }
        // dd($neighborhood);
        return response()->json([
            'status' => true,
            'message' => "neighborhoodcity fetch successfully",
            'neighborhoodcity' => view('retailer.include.neighborhoodcity', compact('neighborhood'))->render(),
        ], 200);
    }
    public function toggleStatus(Request $request)
    {
        $id = jsdecode_userdata($request->id);
        try {
            $class = "App\Models\\{$request->model}";

            if (empty($class)) {
                return response()->json(['status' => 'error', 'message' => ucwords($request->model) . ' not found'], 404);
            }
            $result = $class::where('id', $id)->firstOrFail();


            $field = $request->input('field') ? $request->input('field') : 'status';
            // $statusField = $field ? $field : 'status';
            $status = ($result->$field == 1) ? '0' : '1';

            if ($class == "App\Models\User") {
                $user = \App\Models\User::where('id', $id)->firstOrFail();
                if ($status == '1') {

                    $user->notify(new \App\Notifications\UserStatus($user, $status));
                } else {

                    $user->notify(new \App\Notifications\UserStatus($user, $status));
                }
            }
            if ($class == "App\Models\CmsPage") {
                $model_msg = "CMS page";
            } else {
                $model_msg = $request->message == 'hashtag' ? 'Hashtag' : ucwords($request->model);
            }
            if ($result->update([$field => $status])) {
                if ($status == '1') {
                    return response()->json(['status' => 'success', 'message' => $model_msg . $this->getMsg($field, $status)], 200);
                } else {
                    return response()->json(['status' => 'danger', 'message' => $model_msg . $this->getMsg($field, $status)], 200);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => $model_msg . ' has not been updated.'], 400);
            }
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function getMsg($field, $status)
    {
        $msg = "";
        switch ($field) {
            case ('is_ambassador');
                if ($status == '1') {
                    $msg = " has been added to ambassador successfully";
                } else {
                    $msg = " has been remove from ambassador successfully";
                }
                break;
            case ('newsletter'):
                if ($status == '1') {
                    $msg = " newsletter has been active successfully";
                } else {
                    $msg = " newsletter has been inactive successfully";
                }
                break;
            default:
                if ($status == '1') {
                    $msg = " has been activated";
                } else {
                    $msg = " has been deactivated";
                }
        }
        return $msg;
    }

    public function get_subcategory($id){
        $id = jsdecode_userdata($id);
        if ((int) $id) {
            $category = Category::with(['size_type'])->where('status', '1')->where('id', $id)->first();
            $types = isset($category) && count($category->size_type) > 0 ? $category->size_type : [];
            $sub_categories = Category::where('status', '1')->where('parent_id', $id)->get();
            $sizes = Size::where('status', '1')->get();

            return response()->json($sub_categories);
        }
    }
}
