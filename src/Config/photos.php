<?php

return [
    // catalogs
    'types' => [
        1 => 'Normal',
        2 => 'Mapa',
        3 => 'Fachada',
        4 => 'Interior',
        5 => 'Exterior',
        6 => 'Detalles',
    ],
    // constants
    'valid_image_types' => ['image', 'image/png', 'image/jpeg', 'image/webp'],
    // patterns
    'google_drive_pattern' => "/drive\.google\.com\/file\/d\/.+\/view/",
    'file_id_pattern' => "/([a-z\d_-]{25,})[$\/&?]/i",
];
