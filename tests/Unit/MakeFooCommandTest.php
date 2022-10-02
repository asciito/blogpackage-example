<?php

namespace asciito\BlogPackage\Tests;

use asciito\BlogPackage\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeFooCommandTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_class_foo()
    {
        $fooClass = app_path('Foo/MyFooClass.php');

        // make sure we're starting from a clean state
        if (File::exists($fooClass)) {
            unlink($fooClass);
        }

        $this->assertFalse(File::exists($fooClass));

        // Run the make command
        Artisan::call('make:foo MyFooClass');

        // Assert a new file is created
        $this->assertTrue(File::exists($fooClass));

        $expectedContents = <<<CLASS
        <?php

        namespace App\Foo;

        use JohnDoe\BlogPackage\Foo;

        class MyFooClass implements Foo
        {
            public function myFoo()
            {
                // foo
            }
        }
        
        CLASS;

        $this->assertEquals($expectedContents, file_get_contents($fooClass));
    }
}