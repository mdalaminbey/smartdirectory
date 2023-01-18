<?php

use SmartDirectory\Bootstrap\System\Di\Container;
use SmartDirectory\Bootstrap\System\Di\ContainerException;

class DiContainerTest extends WP_UnitTestCase {

	public Container $container;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before each test.
	 *
	 * @return void
	 */
	public function setUp(): void {

		parent::setUp();

		$this->container = new Container();
	}

	/**
	 * Test dependency binding
	 *
	 * @return void
	 */
	public function test_binding() {

		$this->container->set( PaymentGateway::class, StripePaymentGateway::class );

		$this->assertArrayHasKey( PaymentGateway::class, $this->container->entries );

		$this->assertContains( StripePaymentGateway::class, $this->container->entries );

		$payment_gateway = $this->container->get( PaymentGateway::class );

		$this->assertSame( 'Stripe charged 100$ amount', $payment_gateway->charge( 100 ) );
	}

	/**
	 * Test is class instantiable or not
	 *
	 * @return void
	 */
	public function test_instantiable() {

		$this->expectExceptionMessage( 'Class TestClass does not exist' );

		$this->container->get( 'TestClass' );
	}

	/**
	 * Test with out type hint dependency
	 *
	 * @return void
	 */
	public function test_type_hint() {

		$this->expectException( ContainerException::class );

		$this->container->get( WithOutTypeHintClass::class );
	}

	/**
	 * Test PHP build in type dependency
	 *
	 * @return void
	 */
	public function test_build_type_hint() {

		$this->expectException( ContainerException::class );

		$this->container->get( WithBuildTypeHintClass::class );
	}

	/**
	 * Test dependency with nested
	 *
	 * @return void
	 */
	public function test_dependency() {

		$dependency_class = $this->container->get( DependencyClass::class );

		$this->assertTrue( $dependency_class->dependency_class_one instanceof DependencyClassOne );

		$this->assertTrue( $dependency_class->dependency_class_one->stripe_payment_gateway instanceof StripePaymentGateway );
	}
}

/**
 * Some demo class for help test DI Container
 */
interface PaymentGateway {

	public function charge( float $amount);
}

class StripePaymentGateway implements PaymentGateway {

	public function charge( float $amount ) {
		return 'Stripe charged ' . $amount . '$ amount';
	}
}

class WithOutTypeHintClass {
	public function __construct( $value ) { }
}

class WithBuildTypeHintClass {
	public function __construct( string $value ) { }
}

class DependencyClass {

	public $dependency_class_one;

	public function __construct( DependencyClassOne $dependency_class_one ) {
		$this->dependency_class_one = $dependency_class_one;
	}
}

class DependencyClassOne {

	public $stripe_payment_gateway;

	public function __construct( StripePaymentGateway $stripe_payment_gateway ) {
		$this->stripe_payment_gateway = $stripe_payment_gateway;
	}
}
