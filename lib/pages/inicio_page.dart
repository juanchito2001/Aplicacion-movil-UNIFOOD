import 'package:campusfood/pages/perfil_page.dart';
import 'package:campusfood/pages/config_page.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'menu_page.dart';
import 'carito.dart';
import 'login_page.dart';
import '../models/datos.dart';
import '../providers/provedor_carito.dart';

class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Text(
              'UniFood',
              style: TextStyle(
                fontSize: 30,
                fontWeight: FontWeight.bold,
                color: Colors.grey,
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.shopping_cart_checkout_rounded),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => CartPage()),
              );
            },
          ),
        ],
      ),
      drawer: _buildMinimalDrawer(context),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Container(
              margin: const EdgeInsets.symmetric(vertical: 16.0),
              child: Image.asset(
                'assets/logo.png',
                height: 100,
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0),
            child: TextField(
              decoration: InputDecoration(
                hintText: 'Escribe aquí para buscar...',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(30.0),
                  borderSide: BorderSide(color: Colors.green),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(30.0),
                  borderSide: BorderSide(color: Colors.black54, width: 2),
                ),
                prefixIcon: Icon(Icons.search),
              ),
            ),
          ),
          SizedBox(height: 20),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0),
            child: Text(
              'Promociones del Día',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
          ),
          SizedBox(height: 10),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              itemCount: 6,
              itemBuilder: (context, index) {
                return _buildPromotionItem(
                  context,
                  _promotionTitles[index],
                  _promotionDescriptions[index],
                  _promotionImages[index],
                  _promotionPrices[index],
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMinimalDrawer(BuildContext context) {
    return Drawer(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(
            color: Colors.blueGrey.shade400,
            padding: const EdgeInsets.all(30.0),
            child: Center(
              child: Text(
                'UNIFOOD',
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
          ),
          _buildDrawerItem(
            context,
            icon: Icons.person,
            text: 'Perfil',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => ProfilePage())),
          ),
          _buildDrawerItem(
            context,
            icon: Icons.restaurant_menu,
            text: 'Menú',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => MenuPage())),
          ),
          _buildDrawerItem(
            context,
            icon: Icons.settings,
            text: 'Ajustes',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => SettingsPage())),
          ),
          Spacer(),
          Divider(height: 1, color: Colors.grey.shade300),
          _buildDrawerItem(
            context,
            icon: Icons.exit_to_app,
            text: 'Cerrar Sesión',
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => LoginPage())),
            textColor: Colors.red,
            iconColor: Colors.red,
          ),
        ],
      ),
    );
  }

  Widget _buildDrawerItem(
      BuildContext context, {
        required IconData icon,
        required String text,
        required VoidCallback onTap,
        Color textColor = Colors.black,
        Color iconColor = Colors.blueGrey,
      }) {
    return ListTile(
      leading: Icon(icon, color: iconColor),
      title: Text(
        text,
        style: TextStyle(color: textColor),
      ),
      onTap: onTap,
    );
  }

  Widget _buildPromotionItem(BuildContext context, String title, String description, String imagePath, double price) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16.0),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          children: [
            Image.asset(
              imagePath,
              width: 80,
              height: 80,
              fit: BoxFit.cover,
            ),
            SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 4),
                  Text(description),
                  SizedBox(height: 4),
                  Text('\$${price.toStringAsFixed(2)}', style: TextStyle(color: Colors.green)),
                ],
              ),
            ),
            IconButton(
              icon: Icon(Icons.add_shopping_cart),
              onPressed: () {
                FoodItem newItem = FoodItem(
                  nombre: title,
                  descripcion: description,
                  precio: price,
                  imagen: imagePath,
                );

                Provider.of<CartProvider>(context, listen: false).addToCart(newItem);
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('$title agregado al carrito')),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  final List<String> _promotionTitles = [
    'Pollo al Curry Picante', 'Ensalada César', 'Bandeja paisa', 'Ajiaco', 'Carne asada', 'Frijoles con chuleta'
  ];

  final List<String> _promotionDescriptions = [
    'Sabroso, especiado, irresistible', 'Fresca ensalada con pollo y crutones', 'La esencia del plato tradicional',
    'Sabroso, especiado, irresistible', 'Trozos de carne de res asada', 'Crocante y deliciosa chuleta de cerdo'
  ];

  final List<String> _promotionImages = [
    'assets/albon.jpg', 'assets/verduras.png', 'assets/destacado3.png', 'assets/ajiaco.jpg',
    'assets/estofado.jpg', 'assets/frijolesychuleta.png'
  ];

  final List<double> _promotionPrices = [
    2000, 3000, 2500, 4000, 5000, 4000
  ];
}






