<?php

namespace masterkkm\generator;

use phpDocumentor\Reflection\Types\Self_;
use yii\gii\CodeFile;

/**
 * Генератор моделей для Yii2. Изменена структура генерируемых папок - добавлено (опционально)
 * генерация специального класса для бизнес-кода.
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
            . 'таблицы базы данных. Отличие от стандартного - в герениации дополнительного класса для '
            . 'бизнес-кода. Созданные классы наследуют от основной модели, но получают в качестве связанных '
            . 'моделей аналогичные бизнес-модели, унаследованные от моделей связанных таблиц.';
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
            'ns' => 'Это пространство имен создаваемого класса ActiveRecord, например, <code>app\\models\\scheme</code>',
            'db' => 'Это идентификатор компонента приложения БД.',
            'tableName' => 'Это имя таблицы БД, с которой связан содаваемый класс ActiveRecord, например. <code>post</code>.
                При необходимости имя таблицы может состоять из части схемы БД, например. <code>public.post</code>.
                Имя таблицы может заканчиваться звездочкой, чтобы соответствовать нескольким именам таблиц, например: <code>tbl_*</code>
                будет соответствовать таблицам, имя которых начинается с <code>tbl_</code>. В этом случае несколько классов ActiveRecord
                будет сгенерировано, по одному для каждого совпадающего имени таблицы; и имена классов будут сгенерированы из
                совпадающие символы. Например, таблица <code>tbl_post</code> сгенерирует класс <code>Post</code>.',
            'modelClass' => 'Это имя создаваемого класса ActiveRecord. Имя класса не должно содержать
                часть пространства имен, как указано в «Пространстве имен». Вам не нужно указывать имя класса
                если «Имя таблицы» заканчивается звездочкой, в этом случае будет создано несколько классов ActiveRecord.',
            'standardizeCapitals' => 'Это указывает, должны ли сгенерированные имена классов иметь стандартизированные заглавные буквы. Например,
                имена таблиц, такие как <code>SOME_TABLE</code> или <code>Other_Table</code>, будут иметь имена классов <code>SomeTable</code>
                и <code>OtherTable</code> соответственно. Если флажок не установлен, те же таблицы будут иметь имена классов <code>SOMETABLE</code>.
                и <code>OtherTable</code>.',
            'singularize' => 'Это указывает, должны ли сгенерированные имена классов быть в единственном числе. Например,
            имена таблиц, такие как <code>some_tables</code>, будут иметь имена классов <code>SomeTable</code>.',
            'baseClass' => 'Это базовый класс (ActiveRecord) для создаваемого класса. Это должно быть полное имя класса в пространстве имен.',
            'generateRelations' => 'Указывает, должен ли генератор генерировать отношения на основе ограничения внешнего ключа, которые он обнаруживает
                в базе данных. Обратите внимание, что если ваша база данных содержит слишком много таблиц, вы можете снять этот флажок, чтобы ускорить
                процесс генерации кода.',
            'generateJunctionRelationMode' => 'Это указывает, генерировать-ли отношения соединения с отношениями `viaTable()` или `via()` (модель перехода).
                Убедитесь, что вы также создаете модели соединения при использовании опции «Через модель».',
            'generateRelationsFromCurrentSchema' => 'Это указывает, должен ли генератор генерировать отношения из текущей схемы или из всех доступных схем.',
            'generateLabelsFromComments' => 'Указывает, должен ли генератор генерировать внешние названия атрибутов, используя комментарии соответствующих столбцов БД.',
            'useTablePrefix' => 'Это указывает, нужно ли, в создаваемом классе ActiveRecord учитывать настройку <code>tablePrefix</code> при формировании имени таблицы.
                Например, если имя таблицы — <code>tbl_post</code> и <code>tablePrefix=tbl_</code>, класс ActiveRecord
                вернет имя таблицы в виде <code>{{%post}}</code>.',
            'useSchemaName' => 'Указывает, следует ли включать имя схемы в класс ActiveRecord.
                когда он сгенерирован автоматически. Будет использоваться только схема не по умолчанию.',
            'generateQuery' => 'Указывает, следует ли генерировать ActiveQuery для класса ActiveRecord.',
            'queryNs' => 'Это пространство имен создаваемого класса ActiveQuery, например, <code>app\models</code>.',
            'queryClass' => 'Это имя создаваемого класса ActiveQuery. Имя класса не должно содержать
                часть пространства имен, указанная в «Пространстве имен ActiveQuery». Вам не нужно указывать имя класса
                если «Имя таблицы» заканчивается звездочкой, в этом случае будет создано несколько классов ActiveQuery.',
            'queryBaseClass' => 'Это базовый класс для создаваемого класса ActiveQuery. Это должно быть полное имя класса в пространстве имен.',
            'enableI18N' => 'Указывает, должен ли генератор генерировать строки с помощью метода <code>Yii::t()</code>.
                Установите для этого параметра значение <code>true</code>, если вы планируете сделать свое приложение многоязычным.',
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
        $usesTables = $this->generateUsesTable($relations, $this->businessNs);
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model:
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = $this->generateQuery ? $this->generateQueryClassName($modelClassName) : false;
            $tableRelations = isset($relations[$tableName]) ? $relations[$tableName] : [];
            $tableSchema = $db->getTableSchema($tableName);
            $tableUse = isset($usesTables[$tableName]) ? $usesTables[$tableName] : [];
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
                'usesTable' => $tableUse,
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
        foreach ($relations as $tableName => $relation) {
            foreach ($relation as $item) {
                $className = $item[1];
                $usesTable[$tableName][$className] = "$nameSpace\\$className";
            }
        }
        return $usesTable;
    }
}
