<?php declare(strict_types = 1);

namespace Finwe\PHPStan\Intervention\Image\Reflection;

use Intervention\Image\Image;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\CallableType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\TrueOrFalseBooleanType;
use PHPStan\Type\Type;

class InterventionImageMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	private $methods;

	public function __construct()
	{
		$this->methods = [
			'brightness' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('level', FALSE)], FALSE],
			'contrast' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('level', FALSE)], FALSE],
			'crop' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('width', FALSE), $this->ip('height', FALSE), $this->ip('x'), $this->ip('y')], FALSE],
			'encode' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->mp('format'), $this->ip('quality')], FALSE],
			'exif' => [new MixedType(), FALSE, FALSE, TRUE, [$this->ip('key')], FALSE],
			'fit' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('width', FALSE), $this->ip('height'), $this->cp('callback'), $this->sp('position')], FALSE],
			'greyscale' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [], FALSE],
			'height' => [new IntegerType(TRUE), FALSE, FALSE, TRUE, [], FALSE],
			'heighten' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('height', FALSE), $this->cp('callback')], FALSE],
			'resize' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('width', FALSE), $this->ip('height', FALSE), $this->cp('callback')], FALSE],
			'rotate' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->fp('angle'), $this->sp('bgcolor')], FALSE],
			'widen' => [new ThisType(Image::class, FALSE), FALSE, FALSE, TRUE, [$this->ip('width', FALSE), $this->cp('callback')], FALSE],
			'width' => [new IntegerType(TRUE), FALSE, FALSE, TRUE, [], FALSE],
		];
	}

	public function hasMethod(ClassReflection $classReflection, string $methodName): bool
	{
		return $classReflection->getName() === Image::class
			&& array_key_exists($methodName, $this->methods);
	}

	public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
	{
		$key = $this->methods[$methodName];

		return $this->returnMethodImplementation($key[0], $classReflection, $key[1], $key[2], $key[3], $methodName, $key[4], $key[5]);
	}

	private function returnMethodImplementation(Type $returnType, ClassReflection $declaringClass, bool $static, bool $private, bool $public, string $name, array $parameters, bool $variadic): MethodReflection
	{

		return new class($returnType, $declaringClass, $static, $private, $public, $name, $parameters, $variadic) implements MethodReflection
		{

			private $returnType, $declaringClass, $static, $private, $public, $name, $parameters, $variadic;

			public function __construct(Type $returnType, ClassReflection $declaringClass, bool $static, bool $private, bool $public, string $name, array $parameters, bool $variadic)
			{
				$this->returnType = $returnType;
				$this->declaringClass = $declaringClass;
				$this->static = $static;
				$this->private = $private;
				$this->public = $public;
				$this->name = $name;
				$this->parameters = $parameters;
				$this->variadic = $variadic;
			}

			public function getDeclaringClass(): ClassReflection
			{
				return $this->declaringClass;
			}

			public function isStatic(): bool
			{
				return $this->static;
			}

			public function isPrivate(): bool
			{
				return $this->private;
			}

			public function isPublic(): bool
			{
				return $this->public;
			}

			public function getPrototype(): MethodReflection
			{
				return $this;
			}

			public function getName(): string
			{
				return $this->name;
			}

			/**
			 * @return \PHPStan\Reflection\ParameterReflection[]
			 */
			public function getParameters(): array
			{
				return $this->parameters;
			}

			public function isVariadic(): bool
			{
				return $this->variadic;
			}

			public function getReturnType(): Type
			{
				return $this->returnType;
			}
		};
	}

	private function sp(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new StringType(FALSE), $name, $optional, FALSE, FALSE);
	}

	private function ip(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new IntegerType(FALSE), $name, $optional, FALSE, FALSE);
	}

	private function bp(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new TrueOrFalseBooleanType(FALSE), $name, $optional, FALSE, FALSE);
	}

	private function fp(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new FloatType(FALSE), $name, $optional, FALSE, FALSE);
	}

	private function ap(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new ArrayType(new MixedType(), FALSE), $name, $optional, FALSE, FALSE);
	}

	private function mp(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new MixedType(), $name, $optional, FALSE, FALSE);
	}

	private function cp(string $name, $optional = TRUE)
	{
		return $this->createParameterInstance(new CallableType(TRUE), $name, $optional, FALSE, FALSE);
	}

	private function createParameterInstance(Type $type, string $name, bool $optional, bool $passedByReference, bool $variadic)
	{
		return new class($type, $name, $optional, $passedByReference, $variadic) implements ParameterReflection
		{
			private $type, $name, $optional, $passedByReference, $variadic;

			public function __construct(Type $type, string $name, bool $optional, bool $passedByReference, bool $variadic)
			{
				$this->type = $type;
				$this->name = $name;
				$this->optional = $optional;
				$this->passedByReference = $passedByReference;
				$this->variadic = $variadic;
			}

			public function getName(): string
			{
				return $this->name;
			}

			public function isOptional(): bool
			{
				return $this->optional;
			}

			public function getType(): Type
			{
				return $this->type;
			}

			public function isPassedByReference(): bool
			{
				return $this->passedByReference;
			}

			public function isVariadic(): bool
			{
				return $this->variadic;
			}
		};
	}

}
