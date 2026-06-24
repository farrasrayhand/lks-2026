resource "aws_s3_bucket" "uploads" {
  bucket = var.s3_bucket_name

  tags = {
    Name        = "${var.project_name}-uploads"
    Environment = var.environment
  }
}

resource "aws_s3_bucket_versioning" "uploads" {
  bucket = aws_s3_bucket.uploads.id

  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_server_side_encryption_configuration" "uploads" {
  bucket = aws_s3_bucket.uploads.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

resource "aws_s3_bucket_public_access_block" "uploads" {
  bucket = aws_s3_bucket.uploads.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_policy" "uploads" {
  depends_on = [aws_s3_bucket_public_access_block.uploads]
  bucket     = aws_s3_bucket.uploads.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid    = "AllowCloudFrontOAC"
        Effect = "Allow"
        Principal = { Service = "cloudfront.amazonaws.com" }
        Action   = "s3:GetObject"
        Resource = "${aws_s3_bucket.uploads.arn}/*"
        Condition = {
          StringEquals = {
            "AWS:SourceArn" = aws_cloudfront_distribution.cdn.arn
          }
        }
      },
      {
        Sid    = "AllowEC2RoleUpload"
        Effect = "Allow"
        Principal = { AWS = aws_iam_role.app_ec2.arn }
        Action   = ["s3:PutObject", "s3:GetObject", "s3:DeleteObject"]
        Resource = "${aws_s3_bucket.uploads.arn}/*"
      }
    ]
  })
}
