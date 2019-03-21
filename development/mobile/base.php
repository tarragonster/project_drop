<?php
/**
 * @SWG\Swagger(
 *     schemes={"http", "https"},
 *     host=API_HOST,
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Api for SecondScreen App.",
 *     ),
 * )
 */

/**
 * @SWG\Tag(
 *   name="Authorization",
 *   description="Authorization for the app",
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="accessToken",
 *   name="Authorization-Data",
 *   type="apiKey",
 *   in="header",
 * )
 */