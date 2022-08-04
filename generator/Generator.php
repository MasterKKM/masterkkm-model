<?php

namespace master_kkm\generator;

/**
 * Генератор моделей для Yii2. Добавлено приведение имени таблиуы
 * к строчным буквам, и удаление префиксов таблиц.
 * @package master_kkm\gii\generators\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
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

            ]);
    }
}
