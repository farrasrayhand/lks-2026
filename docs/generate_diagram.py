#!/usr/bin/env python3
"""Generate Kaltim Smart Platform architecture diagram"""

from PIL import Image, ImageDraw, ImageFont
import os

W, H = 1200, 800
img = Image.new("RGB", (W, H), "#ffffff")
draw = ImageDraw.Draw(img)

try:
    font_title = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf", 22)
    font_label = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf", 14)
    font_small = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf", 11)
except OSError:
    font_title = ImageFont.load_default()
    font_label = ImageFont.load_default()
    font_small = ImageFont.load_default()

def box(x1, y1, x2, y2, fill, outline, text="", font=font_label, text_color="#ffffff"):
    draw.rectangle([x1, y1, x2, y2], fill=fill, outline=outline, width=2)
    if text:
        bbox = draw.textbbox((0, 0), text, font=font)
        tw = bbox[2] - bbox[0]
        th = bbox[3] - bbox[1]
        draw.text(((x1 + x2 - tw) / 2, (y1 + y2 - th) / 2), text, fill=text_color, font=font)

def arrow(x1, y1, x2, y2, color="#888888"):
    draw.line([x1, y1, x2, y2], fill=color, width=2)
    draw.polygon([(x2, y2), (x2 - 8, y2 - 5), (x2 - 8, y2 + 5)], fill=color)

def dashed_box(x1, y1, x2, y2, color="#cccccc"):
    for x in range(x1, x2, 8):
        draw.line([(x, y1), (min(x + 4, x2), y1)], fill=color)
        draw.line([(x, y2), (min(x + 4, x2), y2)], fill=color)
    for y in range(y1, y2, 8):
        draw.line([(x1, y), (x1, min(y + 4, y2))], fill=color)
        draw.line([(x2, y), (x2, min(y + 4, y2))], fill=color)

# Title
draw.text((W / 2 - 280, 20), "KALTIM SMART PLATFORM - AWS Architecture", fill="#1a1a2e", font=font_title)

# VPC boundary
dashed_box(20, 60, W - 20, H - 20, "#3a6ea5")
draw.text((40, 65), "VPC (10.0.0.0/16)", fill="#3a6ea5", font=font_label)

# AZ labels
draw.text((W / 2 - 160, 90), "Availability Zone 1 (ap-southeast-3a)", fill="#666", font=font_label)
draw.text((W / 2 + 80, 90), "Availability Zone 2 (ap-southeast-3b)", fill="#666", font=font_label)

# Internet
box(W / 2 - 50, 70, W / 2 + 50, 100, "#e74c3c", "#c0392b", "Internet", font_small)

# IGW
box(W / 2 - 40, 115, W / 2 + 40, 140, "#3498db", "#2980b9", "IGW", font_small)

# ALB Public Subnets
draw.text((140, 170), "Public Subnet AZ1", fill="#2ecc71", font=font_label)
draw.text((W / 2 + 40, 170), "Public Subnet AZ2", fill="#2ecc71", font=font_label)

box(100, 190, 260, 240, "#2ecc71", "#27ae60", "ALB", font_label)
box(W / 2, 190, W / 2 + 160, 240, "#2ecc71", "#27ae60", "ALB", font_label)

# Private App Subnets
draw.text((110, 280), "Private App Subnet AZ1", fill="#e67e22", font=font_label)
draw.text((W / 2 + 10, 280), "Private App Subnet AZ2", fill="#e67e22", font=font_label)

box(100, 310, 260, 370, "#e67e22", "#d35400", "EC2 App", font_label)
box(W / 2, 310, W / 2 + 160, 370, "#e67e22", "#d35400", "EC2 App", font_label)

# Private DB Subnets
draw.text((70, 420), "Private DB Subnet AZ1", fill="#9b59b6", font=font_label)
draw.text((W / 2 - 30, 420), "Private DB Subnet AZ2", fill="#9b59b6", font=font_label)

box(100, 450, 260, 510, "#9b59b6", "#8e44ad", "RDS MySQL", font_label, "#ffffff")
box(W / 2, 450, W / 2 + 160, 510, "#9b59b6", "#8e44ad", "RDS (Standby)", font_label, "#ffffff")
box(100, 525, 260, 565, "#e74c3c", "#c0392b", "ElastiCache", font_label)

# S3 (outside VPC but connected)
box(W / 2 + 250, 450, W / 2 + 400, 510, "#1abc9c", "#16a085", "S3 Bucket", font_label)

# NAT GW
box(40, 340, 80, 400, "#f39c12", "#e67e22", "NAT", font_small)
box(40, 470, 80, 530, "#f39c12", "#e67e22", "NAT", font_small)

# Arrows
arrow(W / 2, 100, W / 2, 115, "#e74c3c")
arrow(W / 2, 140, 180, 190, "#3498db")
arrow(W / 2, 140, W / 2 + 80, 190, "#3498db")
arrow(180, 240, 180, 310, "#888")
arrow(W / 2 + 80, 240, W / 2 + 80, 310, "#888")
arrow(180, 370, 180, 450, "#888")
arrow(W / 2 + 80, 370, W / 2 + 80, 450, "#888")
arrow(200, 370, W / 2 + 325, 450, "#1abc9c")
arrow(180, 370, 300, 470, "#e74c3c")

# Security Group labels
draw.text((300, 200), "SG: ALB\n(80/443\nfrom internet)", fill="#666", font=font_small)
draw.text((300, 330), "SG: App\n(from ALB\nonly)", fill="#666", font=font_small)
draw.text((300, 480), "SG: RDS\n(from App\nonly)", fill="#666", font=font_small)

# Route Tables
draw.text((W - 200, 150), "Route Tables", fill="#555", font=font_label)
draw.text((W - 200, 175), "Public: 0.0.0.0/0 -> IGW", fill="#555", font=font_small)
draw.text((W - 200, 195), "Private: 0.0.0.0/0 -> NAT", fill="#555", font=font_small)

# Legend
draw.text((W - 280, 580), "Legend:", fill="#333", font=font_label)
y_legend = 605
for color, name in [
    ("#2ecc71", "Public Subnet"),
    ("#e67e22", "Private App Subnet"),
    ("#9b59b6", "Private DB Subnet"),
    ("#3498db", "Internet Gateway"),
    ("#e74c3c", "Redis Cache"),
    ("#1abc9c", "S3 Storage"),
]:
    draw.rectangle([W - 280, y_legend, W - 260, y_legend + 15], fill=color, outline="#333")
    draw.text((W - 250, y_legend - 1), name, fill="#333", font=font_small)
    y_legend += 20

# User flow labels
draw.text((W / 2 - 80, 140), "Request Ingress", fill="#e74c3c", font=font_small)
draw.text((240, 270), "Load Balanced", fill="#2ecc71", font=font_small)
draw.text((240, 400), "DB Queries", fill="#9b59b6", font=font_small)

output_path = os.path.join(os.path.dirname(__file__), "architecture-diagram.png")
img.save(output_path, "PNG")
print(f"Diagram saved to {output_path}")
