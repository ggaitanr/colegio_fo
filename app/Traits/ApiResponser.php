<?php
namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		$collection = $this->filterData($collection);
		return $this->successResponse($collection, $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code = 200)
	{
		return $this->successResponse(['data' => $message], $code);
	}

	protected function showQuery($query)
	{
		return $this->successResponse(['data' =>$query], 200);
	}

	protected function filterData(Collection $collection)
	{
		foreach (request()->query() as $query => $value) {
			if (isset($value)) {
				if($query != 'query' && $query != 'page' && $query != 'per_page'){
					if($value === 'true' || $value==='false')
					{
						$value === 'true'? $value=true: $value=false;
					}
					$collection = $collection->where($query, $value)->values();
				}
			}

		}
		//Log::notice('Información: '.$collection);
		return $collection;
	}

	protected function sortData(Collection $collection, $transformer)
	{
		if (request()->has('sort_by')) {
			$attribute = $transformer::originalAttribute(request()->sort_by);
			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}
}