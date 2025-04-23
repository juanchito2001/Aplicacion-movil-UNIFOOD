import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/provedor_carito.dart';
import '../widgets/index_comida.dart';
import '../models/datos.dart';
import 'inicio_page.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'carito.dart';

class MenuPage extends StatefulWidget {
  @override
  _MenuPageState createState() => _MenuPageState();
}

class _MenuPageState extends State<MenuPage> {
  int _selectedIndex =
      0; // Indica la pestaña seleccionada (0: Comidas, 1: Bebidas)
  List<FoodItem> itemsToDisplay =
      []; // Lista que almacena los datos desde la API
  bool isLoading = true; // Indicador para mostrar el cargador
  bool hasError = false; // Indicador para detectar errores

  @override
  void initState() {
    super.initState();
    _fetchData(); // Cargar datos al iniciar
  }

  Future<void> _fetchData() async {
    try {
      final response = await http.get(Uri.parse(
          'http://192.168.0.127:3000/platos')); //'http://192.168.66.157:3000/platos'  univalle
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        setState(() {
          itemsToDisplay = data.map((item) => FoodItem.fromJson(item)).toList();
          isLoading = false;
          hasError = false;
        });
      } else {
        setState(() {
          hasError = true;
          isLoading = false;
        });
        print(
            'Error al cargar los datos: Código de estado ${response.statusCode}');
      }
    } catch (e) {
      setState(() {
        hasError = true;
        isLoading = false;
      });
      print('Error: $e');
    }
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });

    if (index == 1) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => HomePage()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.blueGrey,
        title: Text(
          _selectedIndex == 0 ? 'Menú de Comidas' : 'Menú de Bebidas',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
        automaticallyImplyLeading: false,
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : hasError
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.error_outline, size: 60, color: Colors.red),
                      SizedBox(height: 10),
                      Text('Hubo un problema al cargar los datos.'),
                      ElevatedButton(
                        onPressed: _fetchData,
                        child: Text('¡Intenta de nuevo!'),
                        style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.blueGrey),
                      ),
                    ],
                  ),
                )
              : GridView.builder(
                  padding: const EdgeInsets.all(16.0),
                  gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    childAspectRatio: 0.7,
                    crossAxisSpacing: 16.0,
                    mainAxisSpacing: 16.0,
                  ),
                  itemCount: itemsToDisplay.length,
                  itemBuilder: (context, index) {
                    final FoodItem item = itemsToDisplay[index];
                    return GestureDetector(
                      onTap: () {
                        // Puedes agregar la lógica al hacer clic
                      },
                      child: FoodCard(
                        foodItem: item,
                        imagen: item.imagen,
                        precio: item.precio,
                        onAddToCart: () {
                          Provider.of<CartProvider>(context, listen: false)
                              .addToCart(item);
                        },
                      ),
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => CartPage()),
          );
        },
        child: Icon(Icons.shopping_cart, size: 30),
        backgroundColor: Colors.green,
        tooltip: 'Ver carrito',
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
        items: [
          BottomNavigationBarItem(
            icon: Icon(Icons.restaurant_menu, size: 30),
            label: 'Comidas',
          ),
          BottomNavigationBarItem(
            icon: Container(
              width: 70,
              height: 70,
              decoration: BoxDecoration(
                color: Colors.blueGrey.shade200,
                borderRadius: BorderRadius.circular(35),
              ),
              child: Icon(
                Icons.home_filled,
                color: Colors.white,
                size: 35,
              ),
            ),
            label: 'Inicio',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.local_drink, size: 30),
            label: 'Bebidas',
          ),
        ],
        selectedItemColor: Colors.grey,
        unselectedItemColor: Colors.black45,
        backgroundColor: Colors.blueGrey.shade400,
        type: BottomNavigationBarType.fixed,
        selectedFontSize: 16.0,
        unselectedFontSize: 14.0,
        showUnselectedLabels: true,
      ),
    );
  }
}
