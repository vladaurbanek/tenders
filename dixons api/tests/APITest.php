<?php

/**
 * @property array testData
 */
class APITest extends TestCase
{
    /**
     * APITest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testData = [
            'what' => 'DEPLOY-2016.20',
            'tags' => 'code-release',
            'timestamp' => '1460110095',
        ];
    }

    /**
     *  Test sending data
     */
    public function postTest()
    {
        $this->json('POST', '/logInfo', $this->testData)
            ->seeJson([
                'created' => true,
            ]);
    }
}
