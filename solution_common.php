<?php

/**
 * Удаляет дубликаты из массива, дупликаты определяются по значению параметра id
 *
 * @param array $array Двумерный массив, в массивах второго уровня ключи - названия параметров, значения - значения параметров.
 *
 * @return array
 */
function removeDuplicates(array $array): array {
	$result = array_reduce($array,  function($result, $item) {
		if (!array_key_exists($item['id'], $result)) {
			$result[$item['id']] = $item;
		}
		return $result;
	}, []);

	return array_values($result);
}


/**
 * Сортирует по ключу
 *
 * @param array $array Двумерный массив, в массивах второго уровня ключи - названия параметров, значения - значения параметров.
 * @param string $key Название параметра по котрому небходимо отсортировать
 * @param int $sortOrder Порядок сортировки
 *
 * @return array
 */
function sortBy(array $array, string $key, int $sortOrder = SORT_ASC): array {
	usort($array, fn($item1, $item2) => $sortOrder == SORT_ASC ? $item1[$key] <=> $item2[$key] : $item2[$key] <=> $item1[$key]);
	return $array;
}

/**
 * Фильтрует массив по значению ключа
 *
 * @param array $array Двумерный массив, в массивах второго уровня ключи - названия параметров, значения - значения параметров.
 * @param string $key Название параметра по которому небходимо отсортировать
 * @param mixed $value Значение параметра по которому небходимо отсортировать
 *
 * @return array
 */
function filterBy(array $array, string $key, $value) : array {
	return array_filter($array, fn($item) => $item[$key] === $value);
}

/**
 * Возвращает массив на основе заданного с указанными ключами и значениями
 * Если в исходном массиве есть несколько значений для указанного ключа, в итоговом массиве будет только первое.
 *
 * @param array $array Двумерный массив, в массивах второго уровня ключи - названия параметров, значения - значения параметров.
 * @param string $keyName Название параметра значения которого должны использоваться в качестве ключей
 * @param string $valueName Название параметра значения которого должны использоваться в качестве значений
 *
 * @return mixed
 */
function flip(array $array, string $keyName, string $valueName) {
	return array_reduce($array, function ($result, $item) use($keyName, $valueName) {
		if (!$result[$item[$keyName]]) {
			$result[$item[$keyName]] = $item[$valueName];
		}
		return $result;
	}, []);
}

/**

5. id и названия всех товаров, которые имеют все возможные теги в этой базе:

SELECT id, name FROM goods WHERE
	id IN (SELECT goods_id FROM goods_tags GROUP BY goods_id
		HAVING COUNT(tag_id) = (SELECT COUNT(*) FROM tags))


6. департаменты, в которых есть мужчины, и все они (каждый) поставили высокую оценку (строго выше 5):

SELECT department_id FROM evaluations WHERE gender = true GROUP BY department_id HAVING (MIN(value)) > 5

 */