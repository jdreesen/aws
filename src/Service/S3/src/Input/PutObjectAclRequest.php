<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\RequestPayer;

class PutObjectAclRequest
{
    /**
     * The canned ACL to apply to the object. For more information, see Canned ACL.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     *
     * @var ObjectCannedACL::*|null
     */
    private $ACL;

    /**
     * Contains the elements that set the ACL permissions for an object per grantee.
     *
     * @var AccessControlPolicy|null
     */
    private $AccessControlPolicy;

    /**
     * The bucket name that contains the object to which you want to attach the ACL.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * The base64-encoded 128-bit MD5 digest of the data. This header must be used as a message integrity check to verify
     * that the request body was not corrupted in transit. For more information, go to RFC 1864.&gt;.
     *
     * @see http://www.ietf.org/rfc/rfc1864.txt
     *
     * @var string|null
     */
    private $ContentMD5;

    /**
     * Allows grantee the read, write, read ACP, and write ACP permissions on the bucket.
     *
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * Allows grantee to list the objects in the bucket.
     *
     * @var string|null
     */
    private $GrantRead;

    /**
     * Allows grantee to read the bucket ACL.
     *
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * Allows grantee to create, overwrite, and delete any object in the bucket.
     *
     * @var string|null
     */
    private $GrantWrite;

    /**
     * Allows grantee to write the ACL for the applicable bucket.
     *
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * Key for which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var RequestPayer::*|null
     */
    private $RequestPayer;

    /**
     * VersionId used to reference a specific version of the object.
     *
     * @var string|null
     */
    private $VersionId;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   AccessControlPolicy?: \AsyncAws\S3\Input\AccessControlPolicy|array,
     *   Bucket?: string,
     *   ContentMD5?: string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   Key?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   VersionId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input['ACL'] ?? null;
        $this->AccessControlPolicy = isset($input['AccessControlPolicy']) ? AccessControlPolicy::create($input['AccessControlPolicy']) : null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->ContentMD5 = $input['ContentMD5'] ?? null;
        $this->GrantFullControl = $input['GrantFullControl'] ?? null;
        $this->GrantRead = $input['GrantRead'] ?? null;
        $this->GrantReadACP = $input['GrantReadACP'] ?? null;
        $this->GrantWrite = $input['GrantWrite'] ?? null;
        $this->GrantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectCannedACL::*|null
     */
    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function getAccessControlPolicy(): ?AccessControlPolicy
    {
        return $this->AccessControlPolicy;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function getGrantWrite(): ?string
    {
        return $this->GrantWrite;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function requestBody(): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;

        if (null !== $input = $this->AccessControlPolicy) {
            $document->appendChild($document_AccessControlPolicy = $document->createElement('AccessControlPolicy'));
            $document_AccessControlPolicy->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');

            $input_Grants = $input->getGrants();

            $document_AccessControlPolicy->appendChild($document_AccessControlPolicy_Grants = $document->createElement('AccessControlList'));
            foreach ($input_Grants as $input_GrantsItem) {
                $document_AccessControlPolicy_Grants->appendChild($document_AccessControlPolicy_Grants_Item = $document->createElement('Grant'));

                if (null !== $input_GrantsItem_Grantee = $input_GrantsItem->getGrantee()) {
                    $document_AccessControlPolicy_Grants_Item->appendChild($document_AccessControlPolicy_Grants_Item_Grantee = $document->createElement('Grantee'));
                    $document_AccessControlPolicy_Grants_Item_Grantee->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

                    if (null !== $input_GrantsItem_Grantee_DisplayName = $input_GrantsItem_Grantee->getDisplayName()) {
                        $document_AccessControlPolicy_Grants_Item_Grantee->appendChild($document->createElement('DisplayName', $input_GrantsItem_Grantee_DisplayName));
                    }

                    if (null !== $input_GrantsItem_Grantee_EmailAddress = $input_GrantsItem_Grantee->getEmailAddress()) {
                        $document_AccessControlPolicy_Grants_Item_Grantee->appendChild($document->createElement('EmailAddress', $input_GrantsItem_Grantee_EmailAddress));
                    }

                    if (null !== $input_GrantsItem_Grantee_ID = $input_GrantsItem_Grantee->getID()) {
                        $document_AccessControlPolicy_Grants_Item_Grantee->appendChild($document->createElement('ID', $input_GrantsItem_Grantee_ID));
                    }

                    $input_GrantsItem_Grantee_Type = $input_GrantsItem_Grantee->getType();
                    $document_AccessControlPolicy_Grants_Item_Grantee->setAttribute('xsi:type', $input_GrantsItem_Grantee_Type);

                    if (null !== $input_GrantsItem_Grantee_URI = $input_GrantsItem_Grantee->getURI()) {
                        $document_AccessControlPolicy_Grants_Item_Grantee->appendChild($document->createElement('URI', $input_GrantsItem_Grantee_URI));
                    }
                }

                if (null !== $input_GrantsItem_Permission = $input_GrantsItem->getPermission()) {
                    $document_AccessControlPolicy_Grants_Item->appendChild($document->createElement('Permission', $input_GrantsItem_Permission));
                }
            }

            if (null !== $input_Owner = $input->getOwner()) {
                $document_AccessControlPolicy->appendChild($document_AccessControlPolicy_Owner = $document->createElement('Owner'));

                if (null !== $input_Owner_DisplayName = $input_Owner->getDisplayName()) {
                    $document_AccessControlPolicy_Owner->appendChild($document->createElement('DisplayName', $input_Owner_DisplayName));
                }

                if (null !== $input_Owner_ID = $input_Owner->getID()) {
                    $document_AccessControlPolicy_Owner->appendChild($document->createElement('ID', $input_Owner_ID));
                }
            }
        }

        return $document->hasChildNodes() ? $document->saveXML() : '';
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->ACL) {
            $headers['x-amz-acl'] = $this->ACL;
        }
        if (null !== $this->ContentMD5) {
            $headers['Content-MD5'] = $this->ContentMD5;
        }
        if (null !== $this->GrantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->GrantFullControl;
        }
        if (null !== $this->GrantRead) {
            $headers['x-amz-grant-read'] = $this->GrantRead;
        }
        if (null !== $this->GrantReadACP) {
            $headers['x-amz-grant-read-acp'] = $this->GrantReadACP;
        }
        if (null !== $this->GrantWrite) {
            $headers['x-amz-grant-write'] = $this->GrantWrite;
        }
        if (null !== $this->GrantWriteACP) {
            $headers['x-amz-grant-write-acp'] = $this->GrantWriteACP;
        }
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->VersionId) {
            $query['versionId'] = $this->VersionId;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}?acl";
    }

    /**
     * @param ObjectCannedACL::*|null $value
     */
    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function setAccessControlPolicy(?AccessControlPolicy $value): self
    {
        $this->AccessControlPolicy = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function setGrantWrite(?string $value): self
    {
        $this->GrantWrite = $value;

        return $this;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null !== $this->ACL) {
            if (!ObjectCannedACL::exists($this->ACL)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ACL" when validating the "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->ACL));
            }
        }

        if (null !== $this->AccessControlPolicy) {
            $this->AccessControlPolicy->validate();
        }

        if (null === $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" when validating the "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
        }
    }
}
