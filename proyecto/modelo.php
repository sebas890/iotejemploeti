	<?php 

include_once("conexion.php");
/**
 * 
 */
class Producto 
{
	public $Codigo;
	public $Nombre;
	public $Categoria;
	public $Proveedor;
	public $Precio;
	public $Stock;
	private $Pdo;



	function __construct()
	{
		$this->GenerarConexion();	
	}

	private function GenerarConexion(){
		$objPdo = new Conexion("northwind", "root", "");
		$this->Pdo=$objPdo->Conectar();
	}


	public function Vista(){

					
			$stm = $this->Pdo->prepare("call consultaProducto()");
					$stm->execute();
					$lista = $stm->FetchAll(PDO::FETCH_OBJ);
					$tabla="";

					if ($stm) {
					
						foreach ($lista as $Prod) {

						$tabla = $tabla."<tr>".
								"<td class=cCodigo>". $Prod->ProductID ."</td>".
								"<td class=cNombreP>". $Prod->ProductName ."</td>".
								"<td>". $Prod->UnitPrice ."</td>".
								"<td>". $Prod->UnitsInStock ."</td>".
								"<td>". $Prod->CategoryName ."</td>".
								"<td>". $Prod->CompanyName ."</td>".
								"<td class=cEditar><a href='frm-producto.php?action=Buscar&cod=". $Prod->ProductID."'>A</a></td>".
								"<td class=cEliminar><a href='procesos.php?action=Eliminar&cod=". $Prod->ProductID."'>E</a></td>".
							 "</tr>";
						}
					}
					else{
						$tabla = "<tr><td colspan='9'>No hay registros</td></tr>";
					}
					return $tabla;
	}


	public function Guardar(){
		$stm = $this->Pdo->prepare("call crearProducto(:dato1, :dato2, :dato3, :dato4, :dato5)");
		$stm->bindParam(":dato1", $this->Nombre);
		$stm->bindParam(":dato2", $this->Categoria);
		$stm->bindParam(":dato3", $this->Proveedor);
		$stm->bindParam(":dato4", $this->Precio);
		$stm->bindParam(":dato5", $this->Stock);
		$resultado=$stm->execute();
		if ($resultado==true) {
			echo "<p>Registro Guardado ....</p>";
			echo "<p><a href='vista-producto.php'>Regresar al Listado</a></p>";
		}else {
			echo "Error al Grabar ...";
		}
	}

	public function Actualizar(){
			$stm = $this->Pdo->prepare("call actualizarProducto(:dato1, :dato2, :dato3, :dato4, :dato5, :dato6)");
			$stm->bindParam(":dato1", $this->Codigo);
			$stm->bindParam(":dato2", $this->Nombre);
			$stm->bindParam(":dato3", $this->Categoria);
			$stm->bindParam(":dato4", $this->Proveedor);
			$stm->bindParam(":dato5", $this->Precio);
			$stm->bindParam(":dato6", $this->Stock);
			$resultado=$stm->execute();
			if ($resultado==true) {
				echo "<p>Registro Actualziado ....</p>";
				echo "<p><a href='vista-producto.php'>Regresar al Listado</a></p>";
			}else {
				echo "Error al Grabar ...";
			}
		}

		public function Buscar(){
			$stm = $this->Pdo->prepare("call buscarProducto(:dato1)");
			$stm->bindParam(":dato1", $this->Codigo);
			$stm->execute();
			$reg = $stm->Fetch(PDO::FETCH_OBJ);
			$this->Codigo = $reg->ProductID;
			$this->Nombre = $reg->ProductName;
			$this->Categoria = $reg->CategoryID;
			$this->Proveedor = $reg->SupplierID;
			$this->Precio = $reg->UnitPrice;
			$this->Stock = $reg->UnitsInStock;
		}

		public function Eliminar(){
			$stm = $this->Pdo->prepare("call sp_Eliminar(:dato1)");
			$stm->bindParam(":dato1", $this->Codigo);
			$resultado=$stm->execute();
			if ($resultado==true) {
				echo "<p>Registro Eliminado ....</p>";
				echo "<p><a href='vista-producto.php'>Regresar al Listado</a></p>";
			}else {
				echo "Error al Grabar ...";
			}
		}


}

?>