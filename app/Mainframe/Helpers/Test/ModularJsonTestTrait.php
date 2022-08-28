<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Mainframe\Helpers\Test;

use App\Mainframe\Features\Responder\Response;

/** @mixin SuperadminModularTestCase */
trait ModularJsonTestTrait
{
    /*
    |--------------------------------------------------------------------------
    | Section: JSON based tests
    |--------------------------------------------------------------------------
    |
    */
    /**
     * User can not store invalid element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_json_user_can_not_store_invalid_element()
    {
        # Code: Prepare URL, data etc
        $url = "/{$this->module->route_path}?ret=json";
        $input = [];

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["POST:".$url, $input]);
        $response = $this->post($url, $input);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'code' => 422,
            'status' => 'fail',
            'errors' => [],
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        # Code: Additionally check error messages
        $errors = $this->getErrorsFromResponse($response);
        $this->print(self::MSG_ERRORS_FOUND, [$errors]);

        foreach ($this->defaultErrors() as $expectedError) {
            $this->assertContains($expectedError, $errors,
                self::MSG_EXPECTED_ERROR_NOT_FOUND.$expectedError."\n");
        }

        return $response;
    }

    /**
     * User can create a new element if input is valid
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection@throws \Exception
     */
    public function test_json_user_can_store_valid_element()
    {
        # Code: Prepare URL, data etc
        $url = "/{$this->module->route_path}?ret=json";
        $inputs = $this->inputs();
        $inputs_modified = array_merge(
            $inputs,
            ['redirect_success' => '#new']
        );

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["POST:".$url, $inputs_modified]);
        $response = $this->post($url, $inputs_modified);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check response
        $expectation = [
            'code' => 200,
            'status' => 'success',
            'data' => [],
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        # Code: Additionally check paylaod data
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$inputs]);
        $payload = $this->getPayloadFromResponse($response);

        foreach ($inputs as $key => $value) {
            $this->assertEquals($value, $payload[$key], "Value mismatch for :$key");
        }

        return $response;
    }

    /**
     * Check duplicate fields
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection
     */
    public function test_json_user_can_not_store_duplicate_element()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}?ret=json";
        $input = [
            'name' => $latest->name,
        ];

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["POST:".$url, $input]);
        $response = $this->post($url, $input);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'status' => 'fail',
            'data' => [
                'name' => $latest->name,
            ],
            'validation_errors' => [
                'name' => ["The name has already been taken."]
            ]
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        return $response;
    }

    /**
     * User can view list of element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_json_user_can_view_list()
    {
        # Code: Prepare URL, data etc
        $url = "/{$this->module->route_path}/list/json";

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->get($url);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'data' => [
                "current_page",
                "first_page_url",
                "from",
                "last_page",
                "last_page_url",
                "links" => [],
                "next_page_url",
                "path",
                "per_page",
                "prev_page_url",
                "to",
                "total",
                "items" => []
            ]
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJsonStructure($expectation);

        return $response;
    }

    /**
     * User can view element as a json object
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection
     */
    public function test_json_user_can_view_element()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id?ret=json";

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["GET:".$url]);
        $response = $this->get($url);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            "code" => 200,
            "status" => "success",
            "data" => $latest->toArray()
        ];

        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        return $response;
    }

    /**
     * User can update an element with valid data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection
     */
    public function test_json_user_can_update_element()
    {
        # Code: Prepare URL, data et
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id?ret=json";
        $updates = $this->updateValues();

        # Code: Execute request, check status
        $this->print(self::MSG_UPDATE_ELEMENT, ["PATCH:".$url, $updates]);
        $response = $this->patch($url, $updates);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'code' => 200,
            'status' => 'success',
            'data' => $updates,
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        return $response;
    }

    /**
     * User can update an element with valid data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection
     */
    public function test_json_user_can_resave_an_element_without_changing()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id?ret=json";
        $updates = $latest->toArray();

        # Code: Execute request, check status
        $this->print(self::MSG_UPDATE_ELEMENT, ["PATCH:".$url, $updates]);
        $response = $this->patch($url, $updates);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'code' => 200,
            'status' => 'success',
            'data' => [],
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        return $response;
    }

    /**
     * User can delete an element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_json_user_can_delete_element()
    {
        sleep(1); // Add delay

        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id?ret=json";

        # Code: Execute request, check status
        $this->print(self::MSG_DELETE_ELEMENT, ["DELETE:".$url]);
        $response = $this->delete($url);
        $this->print(self::MSG_GOT_RESPONSE_CONTENT, [$response->getContent()]);
        $response->assertStatus(200);

        # Code: Check additional response data
        $expectation = [
            'code' => 200,
            'status' => 'success',
            'message' => "The ".\Str::singular($this->module->title)." is deleted",
            'data' => [
                'code' => $latest->code,
                'name' => $latest->name,
            ],
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$expectation]);
        $response->assertJson($expectation);

        // Code: Check if it has been soft deleted.
        $this->assertDatabaseMissing($this->module->tableName(), ['id' => $latest->id, 'deleted_at' => null]);
        $this->print(self::MSG_CHECK_DB." :".$this->module->tableName()." soft deleted #".$latest->id);

        return $response;
    }

}