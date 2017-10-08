<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 *
 * @method \App\Model\Entity\Post[] paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {

        $this->paginate = [
            'contain' => ['Users']
        ];
        $posts = $this->paginate($this->Posts);

        $this->set(compact('posts'));
        $this->set('_serialize', ['posts']);
    }

    public function postComments()
    {


        try{
            $post = $this->Posts->get($this->request->params['id'],['contain'=>'Comments']);
        }catch (\Exception $e) {
           return $this->redirect(['action' => 'index']);  
        }


        $this->paginate = [
            'contain' => ['Posts'],
            'limit'=>10
        ];
        $comments = $this->paginate($this->Posts->Comments->find('all',['conditions'=>['post_id'=>$this->request->params['id']]]));
        

        $this->set(compact('comments','post'));
        $this->set('_serialize', ['comments']);
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('post', $post);
        $this->set('_serialize', ['post']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post = $this->Posts->newEntity();
        if ($this->request->is('post')) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $users = $this->Posts->Users->find('list', ['limit' => 200]);
        $this->set(compact('post', 'users'));
        $this->set('_serialize', ['post']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $users = $this->Posts->Users->find('list', ['limit' => 200]);
        $this->set(compact('post', 'users'));
        $this->set('_serialize', ['post']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post has been deleted.'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function apiConsume(){


        //$this->autoRender = false;

        // request get    
        $http = new Client([
                'ssl_verify_peer'=>false,            
                'ssl_verify_host'=>false,
                'ssl_verify_peer_name'=>false,
                'timeout'=>15,
        ]); 

        $response = $http->get('http://jsonplaceholder.typicode.com/posts');

        $datas = json_decode($response->body);


        // save bd
        $count = 0;
        foreach($datas as $data){
                $post = $this->Posts->newEntity();
                $post = $this->Posts->patchEntity($post, (array)$data);
                $post->user_id = $data->userId;
                if($this->Posts->save($post));
                    $count++;
        }

        $this->response->type('application/json');
        $this->response->body(json_encode(['total'=>$count]));
        return $this->response;    
        
    }

    public function get($id = null){


        if(!$id)
            $posts = $this->Posts->find();
        else{
            try{
                $posts = $this->Posts->get($id);  
            }catch (\Exception $e) {
                $posts = ['error'=>'id does not exist'];
            }
        }

        $this->response->type('application/json');
        $this->response->body(json_encode($posts));
        return $this->response;
    }


    public function test(){

        $this->autoRender = false;

        // request get    
        $http = new Client([
                'ssl_verify_peer'=>false,            
                'ssl_verify_host'=>false,
                'ssl_verify_peer_name'=>false,
                'timeout'=>5,
        ]); 

        $response = $http->get('http://localhost/OneDrive/work17/mobly/posts/get');

        $datas = json_decode($response->body); 

        debug($datas);
    }

}
