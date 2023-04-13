# Práctica de relación 1-M

1. Crear proyecto Laravel:

```bash
composer create-project laravel/laravel relationship-one-to-many
```

o:

```bash
laravel new relationship-one-to-many
```

Luego, configurar la base de datos en el archivo ".env".

2. Agregar dependencias de UI y Login:

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
```

3. Ejecutar en 2 terminales:

NPM:

```bash
cd relationship-one-to-many
npm i
npm run dev
```

Artisan:

```bash
cd relationship-one-to-many
php artisan serve
```

Las entidades participantes son empleados y transacciones (un empleado tiene muchas transacciones y cada transacción pertenece solo a un empleado).


4. Crear modelo, controlador y migración para empleado:

Ejecutar: php artisan make:model <Modelo> -mc

```bash
php artisan make:model Employee -mc
```

Salida:

```bash
PS E:\laravel\relationship-one-to-many> php artisan make:model Employee -mc

   INFO  Model [E:\laravel\relationship-one-to-many\app/Models/Employee.php] created successfully.

   INFO  Migration [E:\laravel\relationship-one-to-many\database\migrations/2023_04_13_032605_create_employees_table.php] created successfully.

   INFO  Controller [E:\laravel\relationship-one-to-many\app/Http/Controllers/EmployeeController.php] created successfully.
```

5. Definir la migración de empleados:

```php
Schema::create('employees', function (Blueprint $table) {
	$table->id();
	$table->string('employee_name'); // <--------------
	$table->integer('amount'); // <--------------
	$table->timestamps();
});
```

6. Migrar: php artisan migrate

7. Definir el controlador:

```php
class EmployeeController extends Controller
{
    // devuelve todos los empleados
    public function getEmployees(){
        return response()->json(Employee::all(), 200);
    }

    // devuelve un empleado por ID
    public function getEmployee($id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        return response()->json($employee::find($id), 200);
    }

    // agrega empleado
    public function addEmployee(Request $request){
        $this->validate($request, [
            'employee_name' => 'required|max:1000',
            'amount' => 'required',
        ]);
        $employee = Employee::create($request->all());
        return response($employee, 201);
    }

    // actualiza empleado
    public function updEmployee(Request $request, $id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $employee->update($request->all());
        return response($employee, 200);
    }

    // borra empleado
    public function deleteEmployee($id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $employee->delete();
        return response()->json(['msg'=>'Eliminado Correctamente'],200);
    }
}
```

8. Definir rutas API para el controlador (routes/api.php):

```php
/* Rutas CRUD para Empleado */
Route::get('employee','App\Http\Controllers\EmployeeController@getEmployees');
Route::get('employee/{id}','App\Http\Controllers\EmployeeController@getEmployee');
Route::post('employee','App\Http\Controllers\EmployeeController@addEmployee');
Route::put('employee/{id}','App\Http\Controllers\EmployeeController@updEmployee');
Route::delete('employee/{id}','App\Http\Controllers\EmployeeController@deleteEmployee');
```

9. Crear modelo, migración y controlador para transacciones:

```bash
php artisan make:model Transaction -mc

PS E:\laravel\relationship-one-to-many> php artisan make:model Transaction -mc

   INFO  Model [E:\laravel\relationship-one-to-many\app/Models/Transaction.php] created successfully.

   INFO  Migration [E:\laravel\relationship-one-to-many\database\migrations/2023_04_13_044254_create_transactions_table.php] created successfully.

   INFO  Controller [E:\laravel\relationship-one-to-many\app/Http/Controllers/TransactionController.php] created successfully.
```

10. Definir la tabla de transacciones:

```php
Schema::create('transactions', function (Blueprint $table) {
	$table->id();
	$table->unsignedBigInteger('employee_id')->index();  // <-------------- siempre definir como unsignedBigInteger e indexarlo
	$table->integer('transaction_amount'); // <--------------
	$table->timestamps();

	$table->foreign("employee_id")->references("id")->on("employees")->onDelete("cascade")->onUpdate("cascade"); // <--------------
});
```

11. Migrar: php artisan migrate

12. Definir la relación 1-M:

1-M de empleados a transacciones:

```php
class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_name', 'amount']; // necesario para agregar masivamente

    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
}
```

M-1 de transacciones a empleados:

```php
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'transaction_amount']; // necesario para agregar masivamente

    public function employee()
    {
    	return $this->belongsTo('App\Employee');
    }
}
```

13. Definir el controlador de transacciones:

```php
class TransactionController extends Controller
{
    // devuelve todas las transacciones
    public function getTransactions()
    {
        return response()->json(Transaction::all(), 200);
    }

    // devuelve una transaccion por ID
    public function getTransaction($id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        return response()->json($transaction::find($id), 200);
    }

    // agrega transaccion
    public function addTransaction(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'transaction_amount' => 'required',
        ]);
        $transaction = Transaction::create($request->all());
        return response($transaction, 201);
    }

    // actualiza transaccion
    public function updTransaction(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        $transaction->update($request->all());
        return response($transaction, 200);
    }

    // borra transaccion
    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        if (is_null($transaction)) {
            return response()->json(['msg' => 'Transaccion no encontrada'], 404);
        }
        $transaction->delete();
        return response()->json(['msg' => 'Eliminado Correctamente'], 200);
    }
}
```

14. Definir las rutas del API CRUD de transacciones:

```php
/* Rutas CRUD para Transaccion */
Route::get('transaction','App\Http\Controllers\TransactionController@getTransactions');
Route::get('transaction/{id}','App\Http\Controllers\TransactionController@getTransaction');
Route::post('transaction','App\Http\Controllers\TransactionController@addTransaction');
Route::put('transaction/{id}','App\Http\Controllers\TransactionController@updTransaction');
Route::delete('transaction/{id}','App\Http\Controllers\TransactionController@deleteTransaction');
```



