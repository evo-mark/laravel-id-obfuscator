<?php

return [
    // The seed is used as a basis for obfuscation. The same seed and same ID will result in the same obfuscated ID
    'seed' => 'laravel-id-obfuscator',
    // The final length of the obfuscated ID
    'length' => 8,
    // The alphabet to use in your obfuscated IDs
    'alphabet' => null,
    // Encode foreign keys from model relationships when sending to the frontend if those models are obfuscated
    'encodeForeign' => false
];
