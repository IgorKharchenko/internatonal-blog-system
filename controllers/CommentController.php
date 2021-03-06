<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Comment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @throws HttpException if transaction is unsuccessful
     * @return mixed
     */
    public function actionCreate($post_id)
    {
        if(Yii::$app->user->isGuest) {return $this->redirect(['site/login', 'logined' => 'false']);}
        $model = new Comment();
        $post = Post::findOne($post_id);
        if($model->checkCPrivilegies()) {
            if ($model->load(Yii::$app->request->post())) {
                if($model->saveComment($model->getData())) {
                    $model->updateCommentsCount($post, "count++");
                    return $this->redirect(['post\view', 'id' => $model->id]);
                }
                else throw new HttpException(400, 'Error during save comment info in the database');
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect(['user/index', 'id' => Yii::$app->user->id, 'error' => 'no-rights']);
        }
    }

    /**
     * Updates an existing Comment.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @throws HttpException if transaction is rollbacked
     * @return mixed
     */
    public function actionUpdate($id, $post_id=null)
    {
        if(Yii::$app->user->isGuest) {return $this->redirect(['site/login', 'logined' => 'false']);}
        $model = $this->findModel($id);
        if($model->checkUDPrivilegies($model)) {
            if ($model->load(Yii::$app->request->post())) {
                if($model->saveComment($model))
                    return $this->redirect(['post/view', 'id' => $post_id]);
                else
                    throw new HttpException(400, 'Error during saving comment info in the database');
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect(['user/index', 'id' => Yii::$app->user->id, 'error' => 'no-rights']);
        }
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @throws HttpException if transaction is rollbacked
     * @return mixed
     */
    public function actionDelete($id, $post_id=null)
    {
        if(Yii::$app->user->isGuest) {return $this->redirect(['site/login', 'logined' => 'false']);}
        $model = $this->findModel($id);
        $post = Post::findOne($post_id);
        if($model->checkUDPrivilegies($model)) {
            if($model->deleteComment($model)) {
                $model->updateCommentsCount($post, "count--");
                return $this->redirect(['post/view', 'id' => $post_id]);
            }
            else throw new HttpException(400, 'Error during saving comment info in the database');
        } else {
            return $this->redirect(['user/index', 'id' => Yii::$app->user->id, 'error' => 'no-rights']);
        }
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested comment does not exist.');
        }
    }
}
