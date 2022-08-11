Расширение для генерации более удобной схемы моделей
==============

Неудобство стандартного генератора моделей в том, что он подразумевает изменение моделей после генерацции, а это создает
трудности в бдущем, если придется вносить изменения в базу данных.

Не большое изменение в работе генератора создающего модели по базе данных, позволяет генерировать сразу две модели -
одну содержащую код работы с базой данных, и вторую - пустую, предназанченную для бизнес-кода. В результате формируется
немного другая структура классов, появляется отделный каталог для классов сформированных исключительно для работы с
базой данных, изменение которых не подразумевается. И которые могут быть легко перегенерированны при изменении структуры
базы данных.

    models        - в этом каталоге формируются заготовки классов, наследников классов схем для бизнес-кода.
      |-- scheme  - в этом каталоге формируются классы наследники актив рекод.

Таким образом, можно не менять модели в каталоге схема, тем самым легко перегенерировать их в случае изменения в базе
данных. Кроме того, для удобства переведены подсказки на форме "Генератор моделей".

Installation
------------

Предпочтительный способ установки этого расширения - через [composer](http://getcomposer.org/download/).

Для этого подключите репозиторий в разделе `repositories` файла `composer.json` следующим образом:

    *  *  *
    "repositories": [
      *  *  *
        {
            "type": "github",
            "url": "https://github.com/MasterKKM/masterkkm-model.git"
        }
    ]
    *  *  *

После чего можно использовать команду композера:

    composer require --dev --prefer-dist masterkkm/yii2-model_generator "*"

Или добавть

    "masterkkm/yii2-model_generator": "*"

в `require-dev` секцию Вашего файла `composer.json`. После чего вызвать

    composer install

После установки, нужно подключить генератор в файле конфигурацции
(Для базового шаблона это `config/web.php` для расширеного, смотрите сами по аналогии). Следующим образом:

    $config['modules']['gii'] = [
        *  *  *
        'generators' => [
            'model' => 'masterkkm\generator\Generator'
        ],
        *  *  *
    ];

Тогда в gii появиться пункт "Генератор моделей".

Usage
-----

Зайти в генерато моделей `gii/model` заполнить форму аналогично стандартной форме. Она будет отличатся только
пунктом `Namespace for business code.` Это нэйм-спейс для бизнес-моделей.
