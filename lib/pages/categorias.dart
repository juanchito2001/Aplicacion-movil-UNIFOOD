import 'package:flutter/material.dart';

class CategoryChip extends StatelessWidget {
  final String categoryName;
  final Color backgroundColor;
  final Color labelColor;
  final IconData? icon;

  const CategoryChip({
    Key? key,
    required this.categoryName,
    this.backgroundColor = Colors.greenAccent,
    this.labelColor = Colors.white,
    this.icon,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8.0),
      child: Chip(
        label: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (icon != null) ...[
              Icon(icon, color: labelColor),
              SizedBox(width: 4),
            ],
            Text(
              categoryName,
              style: TextStyle(color: labelColor),
            ),
          ],
        ),
        backgroundColor: backgroundColor,
      ),
    );
  }
}
