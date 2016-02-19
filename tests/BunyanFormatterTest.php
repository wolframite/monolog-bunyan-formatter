<?php
/**
 * Test the Bunyan style formatter
 * @author Wolfram Huesken <woh@m18.io>
 */

namespace Lunatic\Monolog\Formatter;

use Monolog\Logger;

/**
 * @package Formatter
 */
class BunyanFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $formatter = new BunyanFormatter();
        $this->assertEquals(BunyanFormatter::BATCH_MODE_JSON, $formatter->getBatchMode());
        $this->assertEquals(true, $formatter->isAppendingNewlines());

        $formatter = new BunyanFormatter(BunyanFormatter::BATCH_MODE_NEWLINES, false);
        $this->assertEquals(BunyanFormatter::BATCH_MODE_NEWLINES, $formatter->getBatchMode());
        $this->assertEquals(false, $formatter->isAppendingNewlines());
    }

    public function testFormat()
    {
        $formatter = new BunyanFormatter();
        $record = $this->getRecord();

        $result = $formatter->format($record);
        $resultArr = json_decode($result, true);

        // Has EOL
        $this->assertEquals(PHP_EOL, substr($result, -1, 1));

        // Log Level Mapping
        $this->assertEquals($resultArr['level'], $formatter->getBunyanLogLevel($record['level']));

        // Message
        $this->assertEquals($resultArr['msg'], $record['message']);

        // Name
        $this->assertEquals($resultArr['name'], $record['channel']);

        // Version
        $this->assertEquals(BunyanFormatter::BUNYAN_VERSION, $resultArr['v']);

        // Hostname
        $this->assertEquals(gethostname(), $resultArr['hostname']);

        // PID
        $this->assertEquals(getmypid(), $resultArr['pid']);

        $formatter = new BunyanFormatter(BunyanFormatter::BATCH_MODE_JSON, false);
        $result = $formatter->format($record);

        // No EOL
        $this->assertNotEquals(PHP_EOL, substr($result, -1, 1));
    }

    public function testFormatBatch()
    {
        $formatter = new BunyanFormatter();
        $records = array(
            $this->getRecord(Logger::WARNING),
            $this->getRecord(Logger::DEBUG),
        );

        $result = $formatter->formatBatch($records);
        $resultArr = json_decode($formatter->formatBatch($records), true);

        $this->assertCount(2, $resultArr);

        // No EOL
        $this->assertNotEquals(PHP_EOL, substr($result, -1, 1));

        // Is warning
        $record = array_shift($resultArr);
        $this->assertEquals(BunyanFormatter::LEVEL_WARN, $record['level']);

        // Is debug
        $record = array_shift($resultArr);
        $this->assertEquals(BunyanFormatter::LEVEL_DEBUG, $record['level']);
    }

    public function testFormatBatchNewlines()
    {
        $formatter = new BunyanFormatter(BunyanFormatter::BATCH_MODE_NEWLINES);
        $records = $expected = array(
            $this->getRecord(Logger::WARNING),
            $this->getRecord(Logger::DEBUG),
        );

        $result = $formatter->formatBatch($records);
        $resultArr = explode(PHP_EOL, $result);

        $this->assertEquals(1, substr_count($result, "\n"));
        $this->assertCount(2, $resultArr);

        // Is warning
        $record = json_decode(array_shift($resultArr), true);
        $this->assertEquals(BunyanFormatter::LEVEL_WARN, $record['level']);

        // Is debug
        $record = json_decode(array_shift($resultArr), true);
        $this->assertEquals(BunyanFormatter::LEVEL_DEBUG, $record['level']);
    }

    /**
     * Taken from the original monolog package
     * @link https://github.com/Seldaek/monolog/blob/1.x/tests/Monolog/TestCase.php
     * @param int $level
     * @param string $message
     * @param array $context
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return array(
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => array(),
        );
    }
}
