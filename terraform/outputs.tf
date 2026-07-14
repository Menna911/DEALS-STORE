output "rds_endpoint" {
  value = aws_db_instance.deals_store_db.endpoint
}

output "rds_address" {
  value = aws_db_instance.deals_store_db.address
}

output "bucket_name" {
  value = aws_s3_bucket.deals_store_bucket.bucket
}