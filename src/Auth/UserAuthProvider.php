<?php

namespace Jaguar\Core\Security\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UserAuthProvider extends EloquentUserProvider
{    

    protected $request;

    public function __construct(HasherContract $hasher, $model, $request)
    {
        $this->model = $model;
        $this->hasher = $hasher;
        $this->request = $request;        
    }

    public function createRequest()
    {
        $class = '\\'.ltrim($this->request, '\\');

        return new $class;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {   //dd($credentials);
        $request = $this->createRequest();
        if (
            empty($credentials) ||
           //(count($credentials) === 1 && array_key_exists('clave', $credentials))
            (count($credentials) === 1 && array_key_exists($request->getPasswordAttribute(), $credentials))
        ) 
        {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, $request->getPasswordAttribute())) {
                continue;
            }
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        //$plain = $credentials['clave'];        
        $plain = $this->createRequest()->getPasswordValue($credentials);

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

}
