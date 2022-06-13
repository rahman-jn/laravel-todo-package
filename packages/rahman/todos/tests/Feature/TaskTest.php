<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;
use Rahman\Todos\Models\Label;
use Rahman\Todos\Models\Task;

class TaskTest extends TestCase
{
    //use RefreshDatabase;
    use WithFaker;

    /** 
    *Unauthenticated users can't access to store method
    *
    *@return void
    */

    public function test_unsuthenticated_users_cant_access_to_store_method()
    {
        //Get error when call the method befor login
        $response = $this->post('/tasks');
        //User should redirect to login page if not authenticated
        $response->assertStatus(302);
    }

/**
      * Test if authenticated user be able to insert new record in labels table
      *
      *@return Rahman\Todos\Models\Task
      */
      public function store_simple_task_by_user(){
        //Login user to system
        $user = $this->loginUser();
        //Creating task is reated for several times, so moved in a function
        $response = $this->createTask($user->id);

        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseCount('tasklabels', 5);

        return $response;
     }

     /**
      * Edit the task
      *@return void
      */
      public function edit_task(){
        //Create a User  
        //Login user to system
        $user = $this->loginUser();


        //Create a task
        $this->createTask($user->id);
        $task = Task::first();

        //Edit title
        $response = $this->post("/tasks/update/",[
            'id'=> $task->id,
            'title'=> "Editted Title",
            'description'=>  "Editted Description",
        ]);

        $this->assertDatabaseHas('tasks',[
            'title' => 'Editted Title',
            'description' => 'Editted Description'
        ]);

      }


           /**
      * Edit the task
      *@return void
      */
      public function edit_task_only_for_own_tasks(){
        //Create a User  
        $user = $this->loginUser();
        //Create a task
        $task1 = $this->createTask($user);

        //Act as Loginned user
        $user2 = $this->loginUser();
        $task2 = $this->createTask($user2);

        
        //If order changed we have got the error because logged-in user can update own task
        $task = Task::orderBy('id', 'asc')->first();
        //fwrite(STDERR, $task1->id);
        //Edit title
        $response = $this->post("/tasks/update/",[
            'id'=> $task->id,
            'title'=> "Editted Title",
            'description'=>  "Editted Description",
        ]);

        $this->assertDatabaseMissing('tasks',[
            'title' => 'Editted Title',
            'description' => 'Editted Description'
        ]);

      }

      /**
       * User can Change status of task(0->Open, 1->closed)
       * @return void
       */

    public function test_user_can_change_status_of_task(){
        //Create a User  
        $user = $this->loginUser();
        //Create a task
        $task = $this->createTask($user);

        //Get the task from table
        $task = Task::first();

        //Close the Open task
        $response = $this->post("/tasks/update/",[
            'id' => $task->id,
            'status' => "1",
        ]);

        $task->refresh() ;
        //fwrite(STDERR, $task);
        $this->assertEquals('1', $task->status);

        //Open the closed task
        $response = $this->post("/tasks/update/",[
            'id' => $task->id,
            'status' => "0",
        ]);

        $task->refresh() ;
        //fwrite(STDERR, $task);
        $this->assertEquals('0', $task->status);
    }

          /**
       * User can Change status of task(0->Open, 1->closed)
       */

      public function test_log_after_closing_task(){
        //Create a User  
        $user = $this->loginUser();
        //Create a task
        $task = $this->createTask($user);

        //Get the task from table
        $task = Task::first();

        //Close the Open task
        $response = $this->post("/tasks/update/",[
            'id' => $task->id,
            'user_id' => $user->id,
            'status' => "1",
        ]);

        $task->refresh() ;

        Log::shouldReceive('info')
        ->once()
        ->with('Your task closed: '.$task->title);
    }

    /**
     * Show details of task
     * @return \Rahman\Todos\Resources\TaskResource
     */
    public function test_show_task_details(){
        //Create a User  
        $user = $this->loginUser();
        //Create a task
        $task = factory(\Rahman\Todos\Models\Task::class)->create([
            'user_id' => $user->id,
            'title' => 'taskNo1',
            'description' => 'Description of task NO1'
        ]);
        //$task = json_decode(json_encode($task), true);
        
        //die(print_r((array_keys($task))));
        $response = $this->actingAs($user)->get('/tasks/'.$task->id);
        $response = json_decode(json_encode($response), true);
        //die($response['baseResponse']['original']['title']."======". "taskNo1");
        $this->assertEquals($response['baseResponse']['original']['title'], "taskNo1");
     }

     /**
      * User cant see other users tasks
      */
          /**
     * Show details of task
     * @return \Rahman\Todos\Resources\TaskResource
     */
    public function test_show_task_details_only_for_own_tasks(){
        //Create a User  
        $user = $this->loginUser();
        //Another user and logged-in
        $user2 = $this->loginUser();

        //Create a task
        $task = factory(\Rahman\Todos\Models\Task::class)->create([
            'user_id' => $user->id,
            'title' => 'taskNo1',
            'description' => 'Description of task NO1'
        ]);

        //Create task for user2
        $task = factory(\Rahman\Todos\Models\Task::class)->create([
            'user_id' => $user2->id,
            'title' => 'taskNo2',
            'description' => 'Description of task NO2'
        ]);
        //$task = json_decode(json_encode($task), true);
        
        //die(print_r((array_keys($task))));
        $response = $this->actingAs($user)->get('/tasks/'.$task->id);
        $response = json_decode(json_encode($response), true);
        //die(print_r($response['baseResponse']['original']));
        $this->assertEquals(count($response['baseResponse']['original']), 0);
     }

     public function send_email(){
        $mock = \Mockery::mock($this->app['mailer']->getSwiftMailer());
        $this->app['mailer']->setSwiftMailer($mock);
        $mock
            ->shouldReceive('send')
            ->withArgs([\Mockery::on(function($message)
            {
                $this->assertEquals('My subject', $message->getSubject());
                $this->assertSame(['rahman.j88@gmail.com' => null], $message->getTo());
                $this->assertContains('Some string', $message->getBody());
                return true;
            }), \Mockery::any()])
            ->once();
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
//        fwrite(STDERR, $user);
        
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

    /**
     * Helper function to create a task
     * 
     * @return \Rahman\Todos\Models\Task
     */
    public function createTask($user, $title = "My new task", $description="Description for new task"){

        $labels = factory(Label::class, 5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => "My new task",
            'description' => 'Description for new task',
            'labels' => $labels->pluck('id'),
            'user_id' => $user->id,
        ]);

        return $response;
    }
}
