<?php

namespace App\Mainframe\Helpers\Test;

use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use App\User;

trait TestHelperTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|User|object
     */
    public function latestUser()
    {
        return $this->latest(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|User|object
     */
    public function lastUpdatedUser()
    {
        return User::orderBy('updated_at', 'DESC')->first();
    }

    /**
     * Get the 'errors'=>... from a response
     *
     * @param $response
     * @return mixed|null
     */
    public function errors($response)
    {
        return $this->getErrorsFromResponse($response);
    }

    public function getErrorsFromResponse($response)
    {
        return json_decode($response->getContent(), true)['errors'] ?? [];
    }

    /**
     * Get the 'date'=>... from a response
     *
     * @param $response
     * @return mixed|null
     */
    public function payload($response)
    {
        return $this->getPayloadFromResponse($response);
    }

    public function getPayloadFromResponse($response)
    {
        return json_decode($response->getContent(), true)['data'] ?? null;
    }

    /**
     * Get auth_token of latest user
     *
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string|null
     */
    public function getBearerToken()
    {
        return $this->latestUser()->auth_token;
    }

    /**
     * Get last created model
     *
     * @param  $class
     * @return \App\Mainframe\Features\Modular\BaseModule\BaseModule|null
     */
    public function latest($class = null, $print = true)
    {
        if (!$class && isset($this->module)) {
            $class = $this->module->modelInstance();
        }

        /** @var BaseModule $latest */
        $latest = $class::latest()->first();
        if ($print) {
            $this->print(self::MSG_FETCHED_FROM_DB, [get_class($latest), $latest->toArray()]);
        }
        return $latest;
    }

    /**
     * Get last updated model
     *
     * @param  $class
     * @return \App\Mainframe\Features\Modular\BaseModule\BaseModule|null
     */
    public function lastUpdate($class)
    {
        return $class::orderBy('updated_at', 'DESC')->first();
    }

    public function markAsTest($response)
    {
        $payload = $this->payload($response);
        if (isset($payload['id'])) {
            $this->module->modelInstance()->find($payload['id'])
                ->update(['name' => 'TEST-- '.now()]);
        }
        return $this;
    }

    public function print($str = '', $values = null)
    {
        // 📥 🧰 🟥 🟩
        $values = \Arr::wrap($values);
        fwrite(STDOUT, "----------------------------------------------------- \n");
        fwrite(STDOUT, $str."\n\n");

        foreach ($values as $value) {
            fwrite(STDOUT, "🟩	");
            if (is_array($value)) {
                fwrite(STDOUT, print_r($value, true));
            } elseif (isJson($value)) {
                fwrite(STDOUT, print_r(json_decode($value, true), true));

            } else {
                fwrite(STDOUT, $value);
            }
            fwrite(STDOUT, "\n\n");
        }
        // fwrite(STDOUT, "----------------------------------------------------- \n\n");
    }

    /**
     * Print fetched data
     *
     * @param $data
     * @return void
     */
    public function printFetched($data)
    {
        $this->print(self::MSG_FETCHED_FROM_DB, [get_class($data), $data->toArray()]);
    }

    public function printLn($str = '', $values = null)
    {
        // 📥 🧰 🟥 🟩
        $values = \Arr::wrap($values);
        fwrite(STDOUT, $str." ");

        foreach ($values as $value) {
            fwrite(STDOUT, "🟩	");
            if (is_array($value)) {
                fwrite(STDOUT, print_r($value, true));
            } elseif (isJson($value)) {
                fwrite(STDOUT, print_r(json_decode($value, true), true));

            } else {
                fwrite(STDOUT, $value);
            }
            fwrite(STDOUT, "\n");
        }
        fwrite(STDOUT, "\n");
    }
}