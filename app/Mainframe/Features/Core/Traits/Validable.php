<?php

namespace App\Mainframe\Features\Core\Traits;

use Illuminate\Support\MessageBag;
use Validator;

trait Validable
{
    /** @var \Illuminate\Validation\Validator */
    public $validator;

    /** @var MessageBag */
    public $messageBag;

    /**
     * Setter function for $validator
     *
     * @param $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param $messageBag
     * @return $this
     */
    public function setMessageBag($messageBag)
    {
        $this->messageBag = $messageBag;

        return $this;
    }

    /**
     * Retrieve the singleton messageBag
     *
     * @return \Illuminate\Contracts\Foundation\Application|MessageBag|mixed
     */
    public function messageBag()
    {
        if ($this->messageBag) {
            return $this->messageBag;
        }
        $this->messageBag = resolve(MessageBag::class);

        return $this->messageBag;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function validator()
    {

        if ($this->validator) {
            return $this->validator;
        }

        $this->validator = Validator::make([], []);

        return $this->validator;
    }

    /**
     * Add an error message to a key-value pair
     *
     * @param  string  $message
     * @param  string|null  $key
     * @return Validable
     */
    public function error($message, $key = null)
    {
        $key = $key ?: 'errors';
        $this->fieldError($key, $message);

        return $this;
    }

    /**
     * Add a field specific error message
     *
     * @param  string  $key
     * @param  string|null  $message
     * @return $this
     */
    public function fieldError($key, $message = null)
    {
        $message = $message ?: $key.' is not valid';
        $this->validator()->errors()->add($key, $message);
        resolve(MessageBag::class)->add('errors', $message);

        return $this;
    }

    /**
     * Check if the validator has any error
     *
     * @return bool
     */
    public function isInvalid()
    {
        return $this->validator()->messages()->count();
        // return $this->valid ? false : true;
    }

    /**
     * Check if passed
     *
     * @return bool
     */
    public function isValid()
    {
        return !$this->isInvalid();
    }

    /**
     * Message bag related functions
     */

    /**
     * Add message to different keys.
     *
     * @param $bag
     * @param $data
     * @return $this
     */
    public function addToMessageBag($bag, $data)
    {
        $this->messageBag()->add($bag, $data);

        return $this;

    }

    /**
     * Add message under the 'errors' key
     *
     * @param $data
     * @return $this
     */
    public function addErrorMessage($data)
    {
        $this->addToMessageBag('errors', $data);

        return $this;

    }

    /**
     * Add/Merge all the  errors from a validator instance
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function addValidatorErrors($validator)
    {
        $this->addToMessageBag('errors', ['validation_errors' => $validator->messages()->toArray()]);

        return $this;
    }

    /**
     * Add message under the 'messages' key
     *
     * @param $data
     * @return $this
     */
    public function addMessage($data)
    {
        $this->addToMessageBag('messages', $data);

        return $this;
    }

    /**
     * Add message under the 'warnings' key
     *
     * @param $data
     * @return $this
     */
    public function addWarning($data)
    {
        $this->addToMessageBag('warnings', $data);

        return $this;
    }

    /**
     * Add message under the 'debug' key
     *
     * @param $data
     * @return $this
     */
    public function addDebugMessage($data)
    {
        $this->addToMessageBag('debug', $data);

        return $this;
    }

    /**
     * Get messages of a given key
     *
     * @param $key
     * @return mixed|null
     */
    public function getMessages($key)
    {
        if (!$this->messageBag()->count()) {
            return null;
        }

        $messages = $this->messageBag()->messages();

        return $messages[$key] ?? null;

    }

    /**
     * Checks if a key has any message
     *
     * @param $key
     * @return bool
     */
    public function hasMessages($key)
    {
        return $this->getMessages($key) ? true : false;
    }

    /**
     * Get all the entries under 'errors' key
     *
     * @return mixed|null
     */
    public function getErrors()
    {
        return $this->getMessages('errors');
    }

    /**
     * Check if messageBag has any error
     *
     * @return bool
     */
    public function hasErrors()
    {
        return $this->getMessages('errors') ? true : false;
    }

    /**
     * Get errors as flat string
     *
     * @return string
     */
    public function getErrorsAsSting()
    {
        if (!$this->getErrors()) {
            return;
        }
        return implode(' #', \Arr::flatten($this->getErrors()));
    }

    /**
     * @param  \Illuminate\Validation\Validator|\Illuminate\Contracts\Validation\Validator  $validator
     * @return $this
     */
    public function mergeValidatorErrors($validator)
    {
        if (property_exists($validator, 'validator')) {
            $validator = $validator->validator;
        }

        $this->validator()->messages()->merge($validator->messages());

        return $this;
    }
}