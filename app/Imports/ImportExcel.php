<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\InvoiceDocument;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ImportExcel implements ToCollection
{

    protected $invalidRows = [];
    protected $validRows = []; // เก็บข้อมูลแถวที่ผ่านเงื่อนไข
    protected $responses = [];
    protected $create_user = [];
    private $username;
    private $password;

    public function __construct()
    {
        $this->username = env('BASIC_AUTH_USERNAME');
        $this->password = date('dmy') . env('BASIC_AUTH_PASSWORD');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {

            if ($key > 0) {
                $user_id = $row[0];
                $email = $row[1];
                $tel = $row[2];
                $doc_type = $row[3];
                $request_type = $row[4];
                $purchase_from = $row[5];
                $order_no = $row[6];
                $payment_amount = $row[7];
                $first_name = $row[8];
                $last_name = $row[9];
                $building_no = $row[10];
                $building_name = $row[11];
                $village_name = $row[12];
                $moo_no = $row[13];
                $lane = $row[14];
                $road = $row[15];
                $province = $row[16];
                $district = $row[17];
                $subdistrict = $row[18];
                $postal_code = $row[19];
                $taxID = $row[20];
                $branch_no = $row[21];
                $hwh_branch = $row[22];
                $new_order_no = $row[25];


                $check = InvoiceDocument::where('flag_status', 1)
                    ->where('order_no', $order_no)
                    ->first();

                // dd($check);

                if ($check) {
                    $this->invalidRows[] = [
                        'order_no' => $order_no,
                        'status' => $check->status,
                    ];
                    // dd($this->invalidRows);
                    continue;
                }

                $apiResponses = $this->callApi($order_no, $new_order_no, $payment_amount);

                // เก็บข้อมูล API response
                $this->responses = array_merge($this->responses, $apiResponses);

                // เก็บข้อมูลที่ผ่านเงื่อนไขใน validRows
                $this->validRows[] = [
                    'old_order_no' => $order_no,
                    'new_order_no' => $new_order_no,
                    'payment_amount' => $payment_amount,
                ];
                // dd($this->validRows);


                InvoiceDocument::Create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'tel' => $tel,
                    'doc_type' => $doc_type,
                    'request_type' => $request_type,
                    'purchase_from' => $purchase_from,
                    'order_no' => $new_order_no,
                    'payment_amount' => $payment_amount,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'building_no' => $building_no,
                    'building_name' => $building_name,
                    'village_name' => $village_name,
                    'moo_no' => $moo_no,
                    'lane' => $lane,
                    'road' => $road,
                    'province' => $province,
                    'district' => $district,
                    'subdistrict' => $subdistrict,
                    'postal_code' => $postal_code,
                    'taxID' => $taxID,
                    'branch_no' => $branch_no,
                    'hwh_branch' => $hwh_branch,
                    'status' => 1,
                    'import10_6_status' => 1,
                ]);

                InvoiceDocument::where('order_no', $order_no)->update(['flag_status' => 1]);

                // เพิ่มข้อมูลที่ถูกต้องลงใน create_user
                $this->create_user[] = [
                    'user_id' => $user_id,
                    'email' => $email,
                    'tel' => $tel,
                    'doc_type' => $doc_type,
                    'request_type' => $request_type,
                    'purchase_from' => $purchase_from,
                    'order_no' => $new_order_no,
                    'payment_amount' => $payment_amount,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'building_no' => $building_no,
                    'building_name' => $building_name,
                    'village_name' => $village_name,
                    'moo_no' => $moo_no,
                    'lane' => $lane,
                    'road' => $road,
                    'province' => $province,
                    'district' => $district,
                    'subdistrict' => $subdistrict,
                    'postal_code' => $postal_code,
                    'taxID' => $taxID,
                    'branch_no' => $branch_no,
                    'hwh_branch' => $hwh_branch,
                    // 'status' => 1,
                    // 'import10_6_status' => 1,
                ];
            }
            // dd($this->validRows);
        }
    }

    public function getInvalidRows()
    {
        return $this->invalidRows;
    }

    public function getValidRows()
    {
        return $this->validRows;
    }

    public function getResponses()
    {
        return $this->responses;
    }

    public function getCreateUser()
    {
        return array_values($this->create_user); // ทำให้เป็น Indexed Array
    }

    protected function callApi($oldOrderNo, $newOrderNo, $paymentAmount)
    {
        $responses = [];
        $url_0_10_6 = env('E_RECEIPT_10_6_URL') . '/document/update-hero-status-0';
        $url_3_10_6 = env('E_RECEIPT_10_6_URL') . '/document/update-hero-status-3';

        $urls = [$url_0_10_6, $url_3_10_6];

        foreach ($urls as $url) {
            try {
                if ($url === $url_0_10_6) {
                    $order_no = $newOrderNo;
                    $status_update = '0';
                } else {
                    $order_no = $oldOrderNo;
                    $status_update = '3';
                }

                $data = [
                    'docno' => $order_no,
                    'amount' => $paymentAmount,
                ];

                $response = Http::timeout(1600)->withBasicAuth($this->username, $this->password)
                    ->post($url, $data);

                if ($response->successful()) {
                    $responses[] = [
                        'order_no' => $order_no,
                        'status_update' => $status_update,
                        'status' => 'success',
                        'response' => $response->json(),
                    ];
                } else {
                    $responses[] = [
                        'order_no' => $order_no,
                        'status_update' => $status_update,
                        'status' => 'failed',
                        'error' => $response->body(),
                    ];
                }
            } catch (\Exception $e) {
                $responses[] = [
                    'order_no' => $order_no,
                    'status_update' => $status_update,
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $responses;
    }
}
