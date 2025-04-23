import 'package:flutter/material.dart';
import 'database_helper.dart';

class ProductPage extends StatefulWidget {
  @override
  _ProductPageState createState() => _ProductPageState();
}

class _ProductPageState extends State<ProductPage> {
  final DatabaseHelper _databaseHelper = DatabaseHelper();

  List<Map<String, dynamic>> _products = [];

  @override
  void initState() {
    super.initState();
    _loadProducts();
  }

  Future<void> _loadProducts() async {
    final products = await _databaseHelper.getProducts();
    setState(() {
      _products = products;
    });
  }

  Future<void> _addProduct() async {
    await _databaseHelper.insertProduct({
      'name': 'New Product',
      'description': 'Product Description',
      'price': 9.99,
    });
    _loadProducts();
  }

  Future<void> _deleteProduct(int id) async {
    await _databaseHelper.deleteProduct(id);
    _loadProducts();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Products'),
      ),
      body: ListView.builder(
        itemCount: _products.length,
        itemBuilder: (context, index) {
          final product = _products[index];
          return ListTile(
            title: Text(product['name']),
            subtitle: Text('\$${product['price']}'),
            trailing: IconButton(
              icon: Icon(Icons.delete),
              onPressed: () => _deleteProduct(product['id']),
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _addProduct,
        child: Icon(Icons.add),
      ),
    );
  }
}
