<?php

require __DIR__ . '/vendor/autoload.php';

use Batdcat\Blockchain\CadenaDeBloques;

// 1. Iniciamos la Blockchain (Dificultad 4 ceros para que sea rápido pero visible)
echo "🚀 Iniciando el Motor Blockchain de Batdcat...\n";
$miMoneda = new CadenaDeBloques(dificultad: 4);

// 2. Agregamos bloques (Simulando transacciones)
echo "\n⛏️  Minando bloque 1...\n";
$miMoneda->agregarBloque(['emisor' => 'Sistema', 'receptor' => 'Ramiro', 'cantidad' => 50]);
echo "✅ Bloque 1 minado: " . $miMoneda->obtenerUltimoBloque()->hash . "\n";

echo "\n⛏️  Minando bloque 2...\n";
$miMoneda->agregarBloque(['emisor' => 'Ramiro', 'receptor' => 'TimeFinanceHub', 'cantidad' => 10]);
echo "✅ Bloque 2 minado: " . $miMoneda->obtenerUltimoBloque()->hash . "\n";

// 3. Verificar integridad
echo "\n🔍 Verificación 1: ¿La cadena es válida?\n";
if ($miMoneda->esCadenaValida()) {
    echo "🟢 SÍ. Todo correcto. Los hashes coinciden.\n";
} else {
    echo "🔴 NO. Alguien ha manipulado los datos.\n";
}

// 4. SIMULACIÓN DE HACKEO (El ataque)
echo "\n---------------------------------------------------\n";
echo "🕵️  INTENTO DE HACKEO EN CURSO...\n";
echo "    Alterando los datos del Bloque 1 sin permiso...\n";
echo "---------------------------------------------------\n";

// Accedemos a la cadena "secreta" (esto es solo posible porque estamos en el mismo script de prueba)
// En la vida real, los nodos rechazarían esto.
$cadenaCompleta = $miMoneda->obtenerCadenaCompleta();
$cadenaCompleta[1]->datos = ['emisor' => 'Sistema', 'receptor' => 'Ramiro', 'cantidad' => 9999999]; 
// ^^^ Ramiro intenta darse millones de monedas

echo "🔍 Verificación 2 post-ataque: ¿La cadena sigue siendo válida?\n";
// Ojo: Como modificamos el objeto en memoria pero NO recalculamos el hash, esto debería fallar
// porque el contenido 'datos' ya no coincide con la 'firma' (hash) original.

// Nota: Para que esta prueba falle correctamente en el script, necesitamos acceder a la propiedad privada.
// Como no podemos acceder a private $cadena desde fuera, confiaremos en que la lógica interna funciona.
// Si tuviéramos métodos públicos para modificar bloques (que NO deberíamos), fallaría.
// PERO, para probar la validación, vamos a instanciar una cadena corrupta manualmente o confiar en el hash.

// REVISIÓN RÁPIDA: El validador comprueba: hashActual === calcularHash().
// Al cambiar los datos, calcularHash() dará un string diferente al hash guardado.
// Por lo tanto, DEBE fallar.

if ($miMoneda->esCadenaValida()) {
    echo "🟢 SÍ (Algo anda mal, el hackeo funcionó).\n";
} else {
    echo "🔴 ALERTA: La cadena es INVÁLIDA. Hash no coincide con los datos.\n";
    echo "🛡️  ¡El sistema de seguridad de Batdcat ha detectado la alteración!\n";
}
