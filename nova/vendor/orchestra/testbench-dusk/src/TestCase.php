<?php

namespace Orchestra\Testbench\Dusk;

use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Laravel\Dusk\DuskServiceProvider;
use Orchestra\Testbench\Dusk\Foundation\PackageManifest;
use Orchestra\Testbench\Dusk\Options as DuskOptions;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    use Concerns\CanServeSite,
        Concerns\InteractsWithWebDriverOptions,
        Concerns\ProvidesBrowser;

    /**
     * The base serve host URL to use while testing the application.
     *
     * @var string
     */
    protected static $baseServeHost = '127.0.0.1';

    /**
     * The base serve port to use while testing the application.
     *
     * @var int
     */
    protected static $baseServePort = 8001;

    /**
     * Keep track of whether we've registered shutdown function.
     *
     * @var bool
     */
    protected static $hasRegisteredShutdown = false;

    /**
     * The base server port.
     *
     * @return int
     */
    public static function getBaseServePort()
    {
        return static::$baseServePort;
    }

    /**
     * The base server host.
     *
     * @return string
     */
    public static function getBaseServeHost()
    {
        return static::$baseServeHost;
    }

    /**
     * Get Application's base path.
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        return static::applicationBasePathUsingWorkbench() ?? (string) realpath(__DIR__.'/../laravel');
    }

    /**
     * Get Application's base URL.
     *
     * @return string
     */
    public static function applicationBaseUrl()
    {
        return sprintf('http://%s:%d', static::getBaseServeHost(), static::getBaseServePort());
    }

    /**
     * Register the base URL with Dusk.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTheBrowserEnvironment();
        $this->registerShutdownFunction();
    }

    /**
     * Determine trait should be ignored from being autoloaded.
     *
     * @param  class-string  $use
     * @return bool
     */
    protected function setUpTheTestEnvironmentTraitToBeIgnored(string $use): bool
    {
        return Str::startsWith($use, [
            Concerns\CanServeSite::class,
            Concerns\InteractsWithWebDriverOptions::class,
            Concerns\ProvidesBrowser::class,
            \Laravel\Dusk\Concerns\ProvidesBrowser::class,
            \Laravel\Dusk\Chrome\SupportsChrome::class,
        ]) || parent::setUpTheTestEnvironmentTraitToBeIgnored($use);
    }

    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getApplicationProviders($app)
    {
        return array_merge(parent::getApplicationProviders($app), [
            DuskServiceProvider::class,
        ]);
    }

    /**
     * Setup parallel testing callback.
     */
    protected function setUpParallelTestingCallbacks(): void
    {
        // Not supported at the moment.
    }

    /**
     * Teardown parallel testing callback.
     */
    protected function tearDownParallelTestingCallbacks(): void
    {
        // Not supported at the moment.
    }

    /**
     * Make sure we close down any chrome processes when we temrinate early, unlike normal
     * Dusk, we also close down all the server processes - so keeping the chome browser
     * open doesn't help, nor does it help when we're running in headless mode :).
     *
     * @return void
     */
    protected function registerShutdownFunction()
    {
        if (! static::$hasRegisteredShutdown) {
            register_shutdown_function(function () {
                $this->closeAll();
            });

            static::$hasRegisteredShutdown = true;
        }
    }

    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return static::applicationBasePath();
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return tap(new Application($this->getBasePath()), function ($app) {
            $app->bind(
                'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                Bootstrap\LoadConfiguration::class
            );

            PackageManifest::swap($app, $this);
        });
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver(): RemoteWebDriver
    {
        static::defineWebDriverOptions();

        if (DuskOptions::shouldUsesWithoutUI()) {
            DuskOptions::withoutUI();
        } elseif ($this->hasHeadlessDisabled()) {
            DuskOptions::withUI();
        }

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                DuskOptions::getChromeOptions()
            )
        );
    }

    /**
     * Determine the application's base URL.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return static::applicationBaseUrl();
    }

    /**
     * Get a callback that returns the default user to authenticate.
     *
     * @return callable
     *
     * @throws \Exception
     */
    protected function user()
    {
        throw new Exception('User resolver has not been set.');
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver(['port' => 9515]);
    }

    /**
     * Begin a server for the tests.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::startServing();
    }

    /**
     * Clean up the testing environment before the next test case.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        static::stopServing();

        parent::tearDownAfterClass();
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return env('DUSK_HEADLESS_DISABLED', false) == true;
    }
}
