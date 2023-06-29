<?php
/**
 * @category Plugin
 * @package  Advanced Search
 * @author   Diego Garrido 
 */

use \LimeSurvey\Menu\Menu;


class AdvancedSearch extends PluginBase
{
/**
 * @var string
 */
static protected $description = 'Advanced Search for limes'; //descripcion de l que hace el plugin, se ve al momento de activarlo.

/**
 * @var string
 */
static protected $name = 'AdvancedSearch'; //nombre del plugin, tambien se usa para poder redireccionar, ALTERAR

/**
 * @var string
 */
protected $storage = 'DbStorage';

/**
 * Which version of LS we're using (2.5 or 2.06lts)
 * @var string
 */
protected $lsVersion = '2.5';  // Default to 2.5

/**
 * inicializador de eventos del plugin
 *
 * @return void
 */
public function init()
{
    $this->getSurveys();
    $this->subscribe('beforeAdminMenuRender');
}

/**
 * Run before activation
 * @return void
 */

//render etiqueta en barra de adinistracion
public function beforeAdminMenuRender()
{
    $url = $this->api->createUrl(
        'admin/pluginhelper',
        array(
            'sa'     => 'sidebody',
            'plugin' => $this->getName(),
            'method' => 'actionIndex',  // nombre del metodo que lleva a la pag
            )
    );
    
    // Append menu
    $event = $this->getEvent();
    $event->append('extraMenus', array(
        new Menu(array(
            'label' => 'Advanced Search',
            'href'  => $url
        ))
    ));
}
public function actionIndex()
{   
    
    $data = array();
    $getSurveysLink = Yii::app()->createUrl(
        'plugins/direct',
        array(
            'plugin' => 'AdvancedSearch',
            'function' => 'getSurveys',
        )
    );
    $data['YII_CSRF_TOKEN'] = CHtml::hiddenField('YII_CSRF_TOKEN', Yii::app()->request->csrfToken);
    $data['getSurveysLink'] = $getSurveysLink;
    

    
    $content=$this->api->renderTwig(__DIR__ . '/views/index.twig', $data);
    
    //inscripcion de archivos css y js
    $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/js');
    App()->clientScript->registerScriptFile("$assetsUrl/AdvancedSearch.js");
    $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/css');
    App()->clientScript->registerCssFile("$assetsUrl/AdvancedSearch.css");
    
    return $content;
}

/**
 * Remove menu when deactivating.
 * @return void
 */

//funcion antes de que el plugin sea desactivado



/**
 * 
 * @return string JSON
 */
public function getSurveys(){

    //Verificar si exite la propiedad surveyId dentro $_POST

    if (array_key_exists('surveyId', $_GET)){
                
        $filtro=  $_GET['surveyId']; 
        $tablePrefix="lime_tokens_";
        $dataFinal = array();
        $filteredId= array();
        $tokenData= array();
        $names=array();

        $data = Yii::app()->db->createCommand('SELECT sid,active FROM lime_surveys')
        ->queryAll();
        
        $dataLeght = sizeof($data);
        for($i = 0; $i < $dataLeght; $i++){

            if($data[$i]['active']=='Y'){
                $filteredId[]=$data[$i]['sid'];
                $dataFinal[$i]['sid']=$filteredId[$i];
            }
        }

        $filteredIdLeght= sizeof($filteredId);
        for($i = 0; $i < $filteredIdLeght; $i++){
            $table=$tablePrefix.$filteredId[$i];
            $names[]=Yii::app()->db->createCommand('SELECT surveyls_title  FROM lime_surveys_languagesettings WHERE surveyls_survey_id= '.$filteredId[$i])
            ->queryAll();
            
            for($j = 0; $j < 1; $j++){
                $dataFinal[$i]['nameSurvey']=$names[$i][$j]['surveyls_title'];
            }
                
        
            
        }
        for($i = 0; $i <$filteredIdLeght; $i++){
            
            $table=$tablePrefix.$filteredId[$i];
            $tokenData[] = Yii::app()->db->createCommand('SELECT token,firstname,lastname,email  FROM '.$table.' WHERE token= '.$filtro)
            ->queryAll();
            if(count($tokenData[$i])>0){
            for($j = 0; $j <1; $j++){
                
                $dataFinal[$i]['token']=$tokenData[$i][$j]['token'];
                $dataFinal[$i]['firstname']=$tokenData[$i][$j]['firstname'];
                $dataFinal[$i]['lastname']=$tokenData[$i][$j]['lastname'];
                $dataFinal[$i]['email']=$tokenData[$i][$j]['email'];
            }}else{
                unset($dataFinal[$i]);
            }
        }
        echo(json_encode($dataFinal));
        exit;
        }
    }

}
