<?php /** @noinspection ALL */

/** @noinspection PhpUndefinedClassInspection */

namespace App\Mainframe\Helpers\Test;

/** @mixin  SuperadminModularTestCase */
trait ModularHttpTestTrait
{
    /*
    |--------------------------------------------------------------------------
    | Section: HTML based tests
    |--------------------------------------------------------------------------
    |
    */
    public function test_user_can_see_create_form_input_fields()
    {

        # Code: Clean Test data
        $this->cleanTestData();

        # Code: Prepare URL, data etc
        $url = '/'.$this->module->route_path.'/create';

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            $this->module->title
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        # Code: Check if content has following (In given order)
        $contents = $this->inputHtmlMarkupTexts();
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS_IN_ORDER, [$contents]);
        $response->assertSeeInOrder($contents, false);

        return $response;
    }

    /**
     * User can not store invalid element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_not_store_invalid_element()
    {
        # Code: Prepare URL, data etc
        $url = '/'.$this->module->route_path;
        $input = [];

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["POST:".$url, $input]);
        $response = $this->followingRedirects()->post($url, $input);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            'Fail'
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        # Code: Check if content has following error messages
        $errors = $this->defaultErrors();
        $this->print(self::MSG_CHECK_RESPONSE_ERROR_MESSAGES, [$errors]);
        foreach ($errors as $error) {
            $this->printLn(self::MSG_LOOKING_FOR, [$error]);
            $response->assertSee($error);
        }
        return $response;
    }

    /**
     * User can only store element that is valid
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     * @noinspection PhpUndefinedClassInspection
     * @throws \Exception
     */
    public function test_user_can_store_valid_element()
    {
        # Code: Prepare URL, data etc
        $url = '/'.$this->module->route_path;
        $inputs_modified = array_merge(
            $this->inputs(),
            ['redirect_success' => '#new']
        );

        # Code: Execute request, check status
        $this->print(self::MSG_CREATE_ELEMENT, ["POST:".$url, $inputs_modified]);
        $response = $this->followingRedirects()->post($url, $inputs_modified);
        $response->assertStatus(200);

        # Code: Check if content has following
        foreach ($this->inputs() as $key => $value) {
            $this->printLn(self::MSG_LOOKING_FOR, $key.": ".$value);
            $response->assertSee($value);
        }

        return $response;
    }

    /**
     * User can view list of elements in index page (module grid page)
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_view_list()
    {
        # Code: Prepare URL, data etc
        $url = '/'.$this->module->route_path;

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            $this->module->title,
            'View advanced report with filters, excel export etc.'
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        # Code: Check if content has following (In given order)
        $contents = [
            'Create a new '.\Str::lower(\Str::singular($this->module->title)), // View create button
            '<i class="fa fa-plus-circle"></i>'
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS_IN_ORDER, [$contents]);
        $response->assertSeeInOrder($contents, false);

        # Code: Check if content has following (In given order)
        $contents = $this->gridColumns();
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS_IN_ORDER." (Datatable columns) ", [$contents]);
        $response->assertSeeInOrder($contents, false);

        # Code: Prepare URL, data etc
        // See data table JSON output
        $url = '/'.$this->module->route_path.'/datatable/json';

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        return $response;
    }

    /**
     * User can view the element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_view_an_element()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id";

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        return $response;
    }

    /**
     * User can view edit page.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_edit_element()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id/edit";

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            $this->module->title
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->printLn(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        # Code: Check if content has following (In given order)
        $contents = $this->inputHtmlMarkupTexts();
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS_IN_ORDER, [$contents]);
        $response->assertSeeInOrder($contents, false);

        return $response;
    }

    /**
     * User can update an element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_update_element()
    {
        # Code: Prepare URL, data etc
        $latest = $this->latest();

        $url = "/{$this->module->route_path}/$latest->id";
        $updates = $this->updateValues();

        # Code: Execute request, check status
        $this->print(self::MSG_UPDATE_ELEMENT, ["PATCH:".$url, $updates]);
        $response = $this->followingRedirects()->patch($url, $updates);

        # Code: Check if content has following
        $contents = [
            'Success'
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->printLn(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        # Code: Check if content has following
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$updates]);
        foreach ($updates as $key => $value) {
            $this->printLn(self::MSG_LOOKING_FOR, $key.": ".$value);
            $response->assertSee($value);
        }

        return $response;
    }

    /**
     * User can delete an element
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_delete_element()
    {
        sleep(1); // Add a bit of delay

        # Code: Prepare URL, data etc
        $latest = $this->latest();
        $url = "/{$this->module->route_path}/$latest->id?redirect_success=".route($this->module->name.'.index');

        # Code: Execute request, check status
        $this->print(self::MSG_DELETE_ELEMENT, ["DELETE:".$url]);
        $response = $this->followingRedirects()->delete($url);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            $this->module->title
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->printLn(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        // Code: Check if it has been soft deleted.
        $this->assertDatabaseMissing($this->module->tableName(), ['id' => $latest->id, 'deleted_at' => null]);
        $this->print(self::MSG_CHECK_DB." :".$this->module->tableName()." soft deleted #".$latest->id);
        return $response;
    }

    /**
     * User can view report
     *
     * @return \Illuminate\Foundation\Testing\TestResponse|\Illuminate\Testing\TestResponse
     */
    public function test_user_can_view_report()
    {
        # Code: Prepare URL, data etc
        $url = '/'.$this->module->route_path.'/report?submit=Run';

        # Code: Execute request, check status
        $this->print(self::MSG_GET_FROM, ["GET:".$url]);
        $response = $this->followingRedirects()->get($url);
        $response->assertStatus(200);

        # Code: Check if content has following
        $contents = [
            $this->module->title
        ];
        $this->print(self::MSG_CHECK_RESPONSE_CONTAINS, [$contents]);
        foreach ($contents as $content) {
            $this->printLn(self::MSG_CHECK_RESPONSE_CONTAINS, [$content]);
            $response->assertSee($content); // See the module title in header
        }

        return $response;
    }

}