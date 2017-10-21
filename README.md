# GeoCore S3 Image

## Description

[GeoCore] addon that uploads user images to an [Amazon S3] bucket.

The allows for hosting GeoCore on cloud services without persistent storage
and scaling beyond a single instance.

[Amazon S3]: https://aws.amazon.com/s3/
[GeoCore]: https://geodesicsolutions.com/geocore-software.html

## Dependencies

- [AWS SDK for PHP] version 3.
- [ramsey/uuid] version 3.

[AWS SDK for PHP]: https://aws.amazon.com/sdk-for-php/
[ramsey/uuid]: https://github.com/ramsey/uuid

## Installation

1. Ensure the above dependencies are installed.
2. Place the contents of `src` into `addons/s3image`
   under the root of your [GeoCore] install.
3. Install and enable the addon from the GeoCore admin panel.

## Source Code

The [geocore-s3image source] is hosted on GitHub.
Clone the project with

```
$ git clone https://github.com/ourtownrentals/geocore-s3image.git
```

[geocore-s3image source]: https://github.com/ourtownrentals/geocore-s3image

## Contributing

Please submit and comment on bug reports and feature requests.

To submit a patch:

1. Fork it (https://github.com/ourtownrentals/geocore-s3image/fork).
2. Create your feature branch (`git checkout -b my-new-feature`).
3. Make changes.
4. Commit your changes (`git commit -am 'Add some feature'`).
5. Push to the branch (`git push origin my-new-feature`).
6. Create a new Pull Request.

## License

This GeoCore addon is licensed under the MIT license.

## Warranty

This software is provided by the copyright holders and contributors "as is" and
any express or implied warranties, including, but not limited to, the implied
warranties of merchantability and fitness for a particular purpose are
disclaimed. In no event shall the copyright holder or contributors be liable for
any direct, indirect, incidental, special, exemplary, or consequential damages
(including, but not limited to, procurement of substitute goods or services;
loss of use, data, or profits; or business interruption) however caused and on
any theory of liability, whether in contract, strict liability, or tort
(including negligence or otherwise) arising in any way out of the use of this
software, even if advised of the possibility of such damage.
