<?php

namespace App\Http\Controllers\API\Traits;
use stdClass;
use App\Models\GeneralSettings;

trait JsonResponseTrait
{

    public function success($data, $message = '', $statusCode = 200)
    {
        $data = $this->prepareData($data, $message);
        if (empty($data)) {
            return $this->notFound($message, 404);
        }
        $general_settings = new stdClass();
        $general_settings->_gst_percentage = GeneralSettings::where('option', '_gst_percentage')->value('value');
        $general_settings->_delivery_charges = GeneralSettings::where('option', '_delivery_charges')->value('value');
        
        return response()->json([
            'data' => $data,
            'general_settings' =>  $general_settings,
            'message' => $message ? $message : "success",
        ], $statusCode);
    }
    public function withToken($data, $token = '', $message = '', $statusCode = 200)
    {
        $data = $this->prepareData($data, $message);

        if (empty($data)) {
            return $this->notFound($message, 404);
        }
        return response()->json([
            'data' => $data,
            'token' => $token,
            'message' => $message ? $message : "success",
        ], $statusCode);
    }

    public function withCount($data, $message = '', $statusCode = 200 )
    {
        $data = $this->prepareData($data, $message);

        if (empty($data)) {
            return $this->notFound($message, 404);
        }
        $count = count($data);
        $general_settings = new stdClass();
        $general_settings->_gst_percentage = GeneralSettings::where('option', '_gst_percentage')->value('value');
        $general_settings->_delivery_charges = GeneralSettings::where('option', '_delivery_charges')->value('value');
        
        return response()->json([
            'data' => $data,
            'general_settings' => $general_settings,
            'message' => $message ? $message : "success",
            'totalCount' => $count
        ], $statusCode);
    }

    public function error( $message = '', $statusCode = 500)
    {
        return response()->json([
            'message' => $message ? $message : "something went wrong",
        ], $statusCode);
    }

    public function notFound( $message = '', $statusCode = 404)
    {
        return response()->json([
            'message' => $message ? $message : 'Data not found',
        ], $statusCode);
    }

    public function message( $message = '', $statusCode = 200)
    {
        return response()->json([
            'message' => $message ? $message : 'Data not found',
        ], $statusCode);
    }

    private function prepareData($data, $message)
    {
        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            $data = $data->toArray();
        } elseif ($data instanceof \Illuminate\Database\Eloquent\Builder) {
            $data = $data->get()->toArray();
        } elseif ($data instanceof \Illuminate\Support\Collection) {
            if ($data->isEmpty()) {
                return null;
            }
            $data = $data->toArray();
        } elseif ($data instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
            $data = $data->get()->toArray();
        }

        return empty($data) ? null : $data;
    }

}
