<?php declare(strict_types = 1);

namespace Finwe\PHPStan\Intervention\Image\Reflection;

use Intervention\Image\Image;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\CallableType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;

class InterventionImageMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

	private $methods;

	public function __construct()
	{
		$this->methods = [
			'brightness' => [new ThisType(Image::class, false), false, false, true, [$this->ip('level', false)], false],
			'contrast' => [new ThisType(Image::class, false), false, false, true, [$this->ip('level', false)], false],
			'crop' => [new ThisType(Image::class, false), false, false, true, [$this->ip('width', false), $this->ip('height', false), $this->ip('x'), $this->ip('y')], false],
			'encode' => [new ThisType(Image::class, false), false, false, true, [$this->mp('format'), $this->ip('quality')], false],
			'exif' => [new MixedType(), false, false, true, [$this->ip('key')], false],
			'fit' => [new ThisType(Image::class, false), false, false, true, [$this->ip('width', false), $this->ip('height'), $this->cp('callback'), $this->sp('position')], false],
			'greyscale' => [new ThisType(Image::class, false), false, false, true, [], false],
			'height' => [new IntegerType(true), false, false, true, [], false],
			'heighten' => [new ThisType(Image::class, false), false, false, true, [$this->ip('height', false), $this->cp('callback')], false],
			'resize' => [new ThisType(Image::class, false), false, false, true, [$this->ip('width', false), $this->ip('height', false), $this->cp('callback')], false],
			'rotate' => [new ThisType(Image::class, false), false, false, true, [$this->fp('angle'), $this->sp('bgcolor')], false],
			'widen' => [new ThisType(Image::class, false), false, false, true, [$this->ip('width', false), $this->cp('callback')], false],
			'width' => [new IntegerType(true), false, false, true, [], false],
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

	private function sp(string $name, $optional = true)
	{
		return $this->createParameterInstance(new StringType(false), $name, $optional, false, false);
	}

	private function ip(string $name, $optional = true)
	{
		return $this->createParameterInstance(new IntegerType(false), $name, $optional, false, false);
	}

	private function fp(string $name, $optional = true)
	{
		return $this->createParameterInstance(new FloatType(false), $name, $optional, false, false);
	}

	private function mp(string $name, $optional = true)
	{
		return $this->createParameterInstance(new MixedType(), $name, $optional, false, false);
	}

	private function cp(string $name, $optional = true)
	{
		return $this->createParameterInstance(new CallableType(true), $name, $optional, false, false);
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
