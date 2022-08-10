<?php

namespace masterkkm\generator;

use phpDocumentor\Reflection\Types\Self_;
use yii\gii\CodeFile;

/**
 * Генератор моделей для Yii2. Добавлено приведение имени таблиуы
 * к строчным буквам, и удаление префиксов таблиц.
 * @package master_kkm\gii\generators\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
    private const NAME_SPACE_FOR_BUSINES_MODEL = 'app\\models';
    private const NAME_SPACE_FOR_BASE_MODEL = 'app\\models\\scheme';

    public $businessNs;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Генератор моделей';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Этот генератор, аналогично стандартному, создает модель на базе класса ActiveRecord для заданной '
            . 'таблицы базы данных. Отличие от стандартного - в обязательнром приведении имени к строчным буквам '
            . ' и удаление префиксов.';
    }

    /**
     * Названия сохраняемых полей.
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(
            parent::stickyAttributes(),
            [
                'businessNs',
            ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['businessNs', 'default', 'value' => self::NAME_SPACE_FOR_BUSINES_MODEL],
            [['ns'], 'default', 'value' => Self::NAME_SPACE_FOR_BASE_MODEL]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->businessNs = self::NAME_SPACE_FOR_BUSINES_MODEL;
        $this->ns = Self::NAME_SPACE_FOR_BASE_MODEL;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'businessNs' => 'Namespace, где будут располагаться модели создржащие бизнес-код.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'businessNs' => 'Namespace for business code. ',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $usesTable = $this->generateUsesTable($relations, $this->businessNs);
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model:
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = $this->generateQuery ? $this->generateQueryClassName($modelClassName) : false;
            $tableRelations = isset($relations[$tableName]) ? $relations[$tableName] : [];
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'properties' => $this->generateProperties($tableSchema),
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => $tableRelations,
                'relationsClassHints' => $this->generateRelationsClassHints($tableRelations, $this->generateQuery),
                'usesTable' => $usesTable,
            ];
            $files[] = new CodeFile(
                \Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            // query:
            if ($queryClassName) {
                $params['className'] = $queryClassName;
                $params['modelClassName'] = $modelClassName;
                $files[] = new CodeFile(
                    \Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }

            // business model:
            $params['className'] = $modelClassName;
            $files[] = new CodeFile(
                \Yii::getAlias('@' . str_replace('\\', '/', $this->businessNs)) . '/' . $modelClassName . '.php',
                $this->render('business_model.php', $params)
            );
        }

        return $files;
    }

    /**
     * Генерирует таблицу с полными именами используемых бизнес-классов.
     * @param array $relations
     * @param string $nameSpace
     * @return array
     */
    public function generateUsesTable(array $relations, string $nameSpace): array
    {
        $usesTable = [];
        foreach ($relations as $relation) {
            foreach ($relation as $item) {
                $className = $item[1];
                $usesTable[$className] = "$nameSpace\\$className";
            }
        }
        return $usesTable;
    }
}
