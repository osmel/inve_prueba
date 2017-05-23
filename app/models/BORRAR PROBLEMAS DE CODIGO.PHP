19.8500 s

SELECT a.almacen, m.proceso_traspaso, p.precio, p.comentario,
		p.referencia, p.descripcion, p.minimo, c.hexadecimal_color, 
		c.color nombre_color, co.composicion, ca.calidad, COUNT(m.referencia) as 'suma',
		 p.codigo_contable 
FROM (inven_catalogo_productos as p) 
	JOIN inven_catalogo_colores As c ON p.id_color = c.id 
	JOIN inven_catalogo_composicion As co ON p.id_composicion = co.id 
	JOIN inven_catalogo_calidad As ca ON p.id_calidad = ca.id 
	JOIN inven_registros_entradas As m ON m.referencia= p.referencia and m.id_estatus=12 
	JOIN inven_catalogo_almacenes As a ON a.id = m.id_almacen 

WHERE ( ( ( p.referencia LIKE "%%" ) OR (p.descripcion LIKE "%%") OR (CONCAT("Optimo:",p.minimo) LIKE "%%") OR (c.color LIKE "%%") OR (p.comentario LIKE "%%") OR (co.composicion LIKE "%%") OR ( ca.calidad LIKE "%%" ) OR ( p.precio LIKE "%%" ) ) ) 
GROUP BY p.referencia, p.minimo, p.precio 
HAVING ((suma>0) AND (suma < p.minimo))
ORDER BY p.referencia asc 
LIMIT 20,10

 

$this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 




SELECT SQL_CALC_FOUND_ROWS *, 
	 a.almacen, m.proceso_traspaso, p.precio, p.comentario,
	 p.referencia, p.descripcion, p.minimo, c.hexadecimal_color, 
	 c.color nombre_color, co.composicion, ca.calidad, COUNT(m.referencia) as 'suma',
	  p.codigo_contable 
FROM (inven_catalogo_productos as p) JOIN inven_catalogo_colores As c ON p.id_color = c.id 
	JOIN inven_catalogo_composicion As co ON p.id_composicion = co.id 
	JOIN inven_catalogo_calidad As ca ON p.id_calidad = ca.id 
	JOIN inven_registros_entradas As m ON m.referencia= p.referencia and m.id_estatus=12 
	JOIN inven_catalogo_almacenes As a ON a.id = m.id_almacen 

WHERE ( ( ( p.referencia LIKE "%%" ) OR (p.descripcion LIKE "%%") OR (CONCAT("Optimo:",p.minimo) LIKE "%%") OR (c.color LIKE "%%") OR (p.comentario LIKE "%%") OR (co.composicion LIKE "%%") OR ( ca.calidad LIKE "%%" ) OR ( p.precio LIKE "%%" ) ) ) 
	GROUP BY p.referencia, p.minimo, p.precio 
HAVING ((suma>0) AND (suma < p.minimo)) 
ORDER BY suma DESC 
LIMIT 20,10





AHvIX10800125032017164036_4
APsyT0970012503201715304_2
Ahudb6700012603201723017_2



"SQL_CALC_FOUND_ROWS" en la sentencia , y después invocar la "función FOUND_ROWS()":


conocer cuántas filas se hubiesen obtenido sin la cláusula LIMIT


función FOUND_ROWS():  devolverá un número que indica cuántas filas hubiera devuelto el primer si no se hubiese usado la cláusula LIMIT