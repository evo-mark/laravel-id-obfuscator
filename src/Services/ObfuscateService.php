<?php

namespace EvoMark\LarvelIdObfuscator\Services;

use Hashids\Hashids;

class ObfuscateService
{
    private string $seed;
    private int $length;
    private ?string $alphabet;
    private Hashids $encoder;

    public function __construct(array $options)
    {
        $this->seed = $options['seed'];
        $this->length = $options['length'];
        $this->alphabet = $options['alphabet'];

        $config = [
            $this->seed,
            $this->length
        ];
        if (!empty($this->alphabet)) {
            $config[] = $this->alphabet;
        }

        $this->encoder = new Hashids(...$config);
    }

    public function encode(int|string $id): string
    {
        return $this->encoder->encode($id);
    }

    public function decode(int|string $input = ''): ?int
    {
        if (empty($input)) {
            return '';
        } elseif (is_numeric($input)) {
            return $input;
        }

        $output = $this->encoder->decode($input);
        if (count($output) === 0) {
            return null;
        } else if (count($output) === 1) {
            return $output[0];
        } else {
            throw new \Exception('Can only decode single items');
        }
    }
}
