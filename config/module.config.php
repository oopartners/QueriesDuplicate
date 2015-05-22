<?php

return array(
    'view_manager' => array(
        'template_map' => array(
            'zend-developer-tools/toolbar/duplicate-queries-configs' => __DIR__ .'/../view/zend-developer-tools/toolbar/duplicate-queries-configs.phtml'
        )
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'duplicate-queries-configs' => 'DuplicateQueries'
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'duplicate-queries-configs' => 'zend-developer-tools/toolbar/duplicate-queries-configs'
            ),
        )
    )
);