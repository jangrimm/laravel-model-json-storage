<?php

namespace Tests\Unit;

use Faker\Factory;
use File;
use Hash;
use Models\UserJson;
use Okipa\LaravelModelJsonStorage\Models\UserDatabase;
use Tests\ModelJsonStorageTestCase;

class ModelOverrideTest extends ModelJsonStorageTestCase
{
    public $faker;
    public $clearPassword;
    public $data;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->faker = Factory::create();
        $this->clearPassword = $this->faker->password;
        $this->data = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->email,
            'password' => Hash::make($this->clearPassword),
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSave()
    {
        $userDatabase = app(UserDatabase::class)->create($this->data);
        $userJson = app(UserJson::class)->create($this->data);
        $this->assertEquals(
            app(UserDatabase::class)->where('id', $userDatabase->id)->get()->makeVisible($userDatabase->getHidden())->toJson(),
            File::get($userJson->getJsonStoragePath())
        );
        $this->assertFileExists($userJson->getJsonStoragePath());
    }
}
