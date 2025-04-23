class FoodItem {
  final String nombre;
  final String descripcion;
  final String imagen;
  final double precio;
  int cantidad;

  FoodItem({
    required this.nombre,
    required this.descripcion,
    required this.imagen,
    required this.precio,
    this.cantidad = 1,
  });

  factory FoodItem.fromJson(Map<String, dynamic> json) {
    return FoodItem(
      nombre: json['nombre'],
      descripcion: json['descripcion'],
      imagen: json['imagen'],
      precio: json['precio'].toDouble(),
      cantidad: json.containsKey('cantidad') ? json['cantidad'] : 1,
    );
  }
  //convierte food item a carditem con el to
  CartItem toCartItem() {
    return CartItem(
      name: this.nombre,
      price: this.precio,
      quantity: this.cantidad,
    );
  }
}

class CartItem {
  final String name;
  final double price;
  int quantity;

  CartItem({
    required this.name,
    required this.price,
    required this.quantity,
  });

  void incrementarCantidad() {
    quantity++;
  }

  void decrementarCantidad() {
    if (quantity > 1) {
      quantity--;
    }
  }
}



