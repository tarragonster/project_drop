<?php

/**
 * @SWG\Tag(
 *   name="Authorization",
 *   description="Authorization for the app",
 * )
 * @SWG\Tag(
 *   name="Account",
 *   description="User APIs",
 * )
 * @SWG\Tag(
 *   name="Collection",
 *   description="Collection APIs",
 * )
 * @SWG\Tag(
 *   name="Story",
 *   description="Story APIs",
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="userAgents",
 *   name="X-User-Agents",
 *   type="apiKey",
 *   in="header",
 * )
 * @SWG\SecurityScheme(
 *   securityDefinition="accessToken",
 *   name="Authorization-Data",
 *   type="apiKey",
 *   in="header",
 * )
 */