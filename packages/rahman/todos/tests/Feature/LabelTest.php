<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Rahman\Todos\Models\Label;
use Rahman\Todos\Models\Task;
use Rahman\Todos\Models\TaskLabel;

/**
 * @group Tests
 * Automatic tests for Tasks
 */

class LabelTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    /** 
    *Unauthenticated users can't access to store method
    *
    *@return void
    */

    public function unsuthenticated_users_cant_access_to_store_method()
    {
        //Get error when call the method befor login
        $response = $this->post('/labels');
        //User should redirect to login page if not authenticated
        $response->assertStatus(302);
    }


    /**
     * Store a simple lable to database
     * 
     * @return void
     */

     public function store_simple_label(){
        //Login user to system
        $user = $this->loginUser();
        factory(Label::class)->create([
            'user_id' => $user->id,
        ]);
 
        $this->assertDatabaseCount('labels', 1);
     }

     /**
      * Test if authenticated user be able to insert new record in labels table
      *
      *@return Rahman\Todos\Models\Label
      */
     public function store_simple_label_by_user(){
        //Login user to system
        $user = $this->loginUser();

        $response = $this->actingAs($user)->post('/labels', [
            'title' => "Emergency",
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseCount('labels', 1);

        return $response;
     }

     /**
      * Test if duplicated label doesn't store in table
      *
      *@return void
      */
      public function duplicated_label_doesnt_store(){
        //Login user to system
        $user = $this->loginUser();
        //First create a label
        factory(Label::class)->create([
            'title' => "Emergency",
            'user_id' => $user->id,
        ]);
        //Then call this method in order to act as loggined user with same entries 
        $response = $this->test_store_simple_label_by_user();

        $response->assertStatus(400);
      }

      /**
       * If too short title entered the error should be thrown
       * 
       * @bodyParam title string
       * @bodyParam user_id int
       * 
       * @return void
       */
      public function too_short_title_forbiddened(){
        //Login user to system
        $user = $this->loginUser();
        //Minimum allowed length is 3
        $response = $this->actingAs($user)->post('/labels', [
        'title' => "Em",
        'user_id' => $user->id,
    ]);

        $this->assertDatabaseMissing('labels', [
            'title' => "Em"
        ]);
      }

      /**
       * Get Label ApI Resource and counting the user tasks have this label
       */
      public function test_label_api_rsource_counting_label_tasks(){
        //Login user to system
        $user = $this->loginUser();

        //Creating 4 labels
        factory(Label::class, 50)->create([
            'user_id' => $user->id
        ]);
        $labels = Label::get();

        //creating 7 tasks contains those labels
        factory(Task::class, 27)->create([
            'user_id' => $user->id
        ]);
        $tasks = Task::where('user_id', $user->id)->get();

        //Assigne labels to tasks by random
        for($i=0; $i<10; $i++){
            factory(TaskLabel::class)->create([
            'task_id' => $tasks->random()->getKey(),
            'label_id' => $labels->random()->getKey()
        ]);
        }
        $response = $this->actingAs($user)->get('/labels');
        $i=0;
        foreach($labels as $label){
            $taskLabelsCount = TaskLabel::
            join('tasks', 'tasklabels.task_id', '=', 'tasks.id')
            ->where([
                'tasklabels.label_id' => $label->id,
                'tasks.user_id' => $user->id
            ])
            ->count();
            $this->assertEquals($response[$i]['user_label_tasks'], $taskLabelsCount);
            // if($response[$i]['user_label_tasks'] > 0)
            // fwrite(STDERR,$label->id."----". $taskLabelsCount."-----".$response[$i]['user_label_tasks']."\n");
            $i++;
        }

      }

    /**
     * Helper method for login user
     * 
     * @return \App\Models\User
     */
    public function loginUser(){
        $user = factory(\App\Models\User::class)->create([
            'password' => 'password'
        ]);
        //fwrite(STDERR, $user);
        
        $this->assertDatabaseHas('users', [
            'email' => $user->email ,
        ]);

        $response = $this->actingAs($user)->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);


        return $user;
    }

}
